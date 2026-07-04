<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandeController extends Controller
{
    public function index(Request $request)
    {
        $query = Commande::with(['fournisseur', 'client', 'lignes']);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->statut) {
            $query->where('statut', $request->statut);
        }

        $commandes = $query->latest()->paginate(15);

        return view('commandes.index', compact('commandes'));
    }

    public function create()
    {
        $fournisseurs = Fournisseur::where('actif', true)->orderBy('societe')->get();
        $clients      = Client::where('actif', true)->orderBy('societe')->get();
        $produits     = Produit::where('actif', true)->orderBy('nom')->get();

        return view('commandes.create', compact('fournisseurs', 'clients', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'                => 'required|in:fournisseur,client',
            'fournisseur_id'      => 'required_if:type,fournisseur|nullable|exists:fournisseurs,id',
            'client_id'           => 'required_if:type,client|nullable|exists:clients,id',
            'date_commande'       => 'nullable|date',
            'notes'               => 'nullable|string',
            'produits'            => 'required|array|min:1',
            'produits.*.id'       => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix'     => 'required|numeric|min:0',
        ]);

        // ── Vérification stock pour commande CLIENT ──
        if ($request->type === 'client') {
            $erreurs = [];
            foreach ($request->produits as $ligne) {
                $produit = Produit::find($ligne['id']);
                if ($produit && $produit->quantite_stock < $ligne['quantite']) {
                    $erreurs[] = "Stock insuffisant pour « {$produit->nom} » — disponible : {$produit->quantite_stock} {$produit->unite}, demandé : {$ligne['quantite']}";
                }
            }
            if (!empty($erreurs)) {
                return back()->withErrors($erreurs)->withInput();
            }
        }

        $commande = DB::transaction(function () use ($request) {
            $commande = Commande::create([
                'numero'         => $this->genererNumero($request->type),
                'type'           => $request->type,
                'fournisseur_id' => $request->type === 'fournisseur' ? $request->fournisseur_id : null,
                'client_id'      => $request->type === 'client' ? $request->client_id : null,
                'statut'         => 'en_attente',
                'date_commande'  => $request->date_commande ?? now(),
                'notes'          => $request->notes,
                'user_id'        => auth()->id(),
            ]);

            foreach ($request->produits as $ligne) {
                $commande->lignes()->create([
                    'produit_id'    => $ligne['id'],
                    'quantite'      => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                ]);
            }

            return $commande;
        });

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande créée avec succès !');
    }

    public function show(Commande $commande)
    {
        $commande->load(['lignes.produit', 'fournisseur', 'client', 'user', 'facture']);
        return view('commandes.show', compact('commande'));
    }

    public function edit(Commande $commande)
    {
        if ($commande->statut !== 'en_attente' || $commande->facture) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $commande->load('lignes.produit');
        $produits     = Produit::with('categorie')->where('actif', true)->get();
        $fournisseurs = Fournisseur::where('actif', true)->orderBy('societe')->get();
        $clients      = Client::where('actif', true)->orderBy('societe')->get();

        return view('commandes.edit', compact('commande', 'produits', 'fournisseurs', 'clients'));
    }

    public function update(Request $request, Commande $commande)
    {
        if ($commande->statut !== 'en_attente' || $commande->facture) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }

        $request->validate([
            'type'                => 'required|in:fournisseur,client',
            'fournisseur_id'      => 'required_if:type,fournisseur|nullable|exists:fournisseurs,id',
            'client_id'           => 'required_if:type,client|nullable|exists:clients,id',
            'date_commande'       => 'required|date',
            'notes'               => 'nullable|string',
            'produits'            => 'required|array|min:1',
            'produits.*.id'       => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix'     => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $commande) {
            $commande->update([
                'type'           => $request->type,
                'fournisseur_id' => $request->type === 'fournisseur' ? $request->fournisseur_id : null,
                'client_id'      => $request->type === 'client' ? $request->client_id : null,
                'date_commande'  => $request->date_commande,
                'notes'          => $request->notes,
            ]);

            $commande->lignes()->delete();

            foreach ($request->produits as $ligne) {
                $commande->lignes()->create([
                    'produit_id'    => $ligne['id'],
                    'quantite'      => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                ]);
            }
        });

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande mise à jour !');
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate(['statut' => 'required|in:en_attente,confirmee,recue_livree,annulee']);

        $ancienStatut  = $commande->statut;
        $nouveauStatut = $request->statut;

        // ── Vérification stock si commande CLIENT passe en Livrée ──
        if ($commande->type === 'client' && $nouveauStatut === 'recue_livree' && $ancienStatut !== 'recue_livree') {
            $commande->load('lignes.produit');
            $erreurs = [];
            foreach ($commande->lignes as $ligne) {
                $produit = $ligne->produit;
                if ($produit && $produit->quantite_stock < $ligne->quantite) {
                    $erreurs[] = "Stock insuffisant pour « {$produit->nom} » — disponible : {$produit->quantite_stock} {$produit->unite}";
                }
            }
            if (!empty($erreurs)) {
                return back()->withErrors($erreurs);
            }
        }

        DB::transaction(function () use ($commande, $ancienStatut, $nouveauStatut) {
            $commande->update(['statut' => $nouveauStatut]);

            // ── Mouvement automatique quand statut → recue_livree ──
            if ($nouveauStatut === 'recue_livree' && $ancienStatut !== 'recue_livree') {
                $commande->load('lignes.produit');

                foreach ($commande->lignes as $ligne) {
                    $produit = Produit::lockForUpdate()->find($ligne->produit_id);
                    if (!$produit) continue;

                    $stockAvant = $produit->quantite_stock;

                    if ($commande->type === 'fournisseur') {
                        $stockApres = $stockAvant + $ligne->quantite;
                        $type = 'entree';
                    } else {
                        $stockApres = $stockAvant - $ligne->quantite;
                        $type = 'sortie';
                    }

                    $produit->update(['quantite_stock' => $stockApres]);

                    \App\Models\Mouvement::create([
                        'produit_id'     => $produit->id,
                        'user_id'        => auth()->id(),
                        'type'           => $type,
                        'quantite'       => $ligne->quantite,
                        'stock_avant'    => $stockAvant,
                        'stock_apres'    => $stockApres,
                        'motif'          => 'Commande ' . $commande->numero,
                        'reference_doc'  => $commande->numero,
                        'fournisseur_id' => $commande->type === 'fournisseur' ? $commande->fournisseur_id : null,
                        'client_id'      => $commande->type === 'client' ? $commande->client_id : null,
                    ]);
                }
            }
        });

        $msg = $nouveauStatut === 'recue_livree'
            ? 'Statut mis à jour — mouvements de stock enregistrés automatiquement !'
            : 'Statut mis à jour !';

        return redirect()->route('commandes.show', $commande)->with('success', $msg);
    }

    public function destroy(Commande $commande)
    {
        $commande->delete();
        return redirect()->route('commandes.index')->with('success', 'Commande supprimée !');
    }

    public function pdf(Commande $commande)
    {
        $commande->load(['fournisseur', 'client', 'lignes.produit']);
        $pdf = Pdf::loadView('pdf.commande', compact('commande'))->setPaper('a4', 'portrait');
        return $pdf->download('commande-' . $commande->numero . '.pdf');
    }

    private function genererNumero(string $type): string
    {
        $prefixe = $type === 'fournisseur' ? 'CMD-F' : 'CMD-C';
        $annee   = now()->year;

        $dernier  = Commande::where('type', $type)->whereYear('created_at', $annee)->count();
        $prochain = $dernier + 1;

        return sprintf('%s-%d-%04d', $prefixe, $annee, $prochain);
    }
}