<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $query = Mouvement::with(['produit', 'user', 'fournisseur', 'client']);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->produit_id) {
            $query->where('produit_id', $request->produit_id);
        }
        if ($request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->latest()->paginate(20);
        $produits   = Produit::where('actif', true)->get();

        return view('mouvements.index', compact('mouvements', 'produits'));
    }

    public function create()
    {
        $produits     = Produit::with('categorie')->where('actif', true)->get();
        $fournisseurs = \App\Models\Fournisseur::where('actif', true)->orderBy('societe')->get();
        $clients      = \App\Models\Client::where('actif', true)->orderBy('societe')->get();

        return view('mouvements.create', compact('produits', 'fournisseurs', 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id'     => 'required|exists:produits,id',
            'type'           => 'required|in:entree,sortie',
            'quantite'       => 'required|integer|min:1',
            'motif'          => 'nullable|string|max:255',
            'reference_doc'  => 'nullable|string|max:100',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'client_id'      => 'nullable|exists:clients,id',
        ]);

        DB::transaction(function () use ($request) {
            $produit     = Produit::lockForUpdate()->findOrFail($request->produit_id);
            $stock_avant = $produit->quantite_stock;

            if ($request->type === 'sortie' && $stock_avant < $request->quantite) {
                throw new \Exception('Stock insuffisant. Disponible : ' . $stock_avant);
            }

            $stock_apres = $request->type === 'entree'
                ? $stock_avant + $request->quantite
                : $stock_avant - $request->quantite;

            $produit->update(['quantite_stock' => $stock_apres]);

            Mouvement::create([
                'produit_id'     => $produit->id,
                'user_id'        => auth()->id(),
                'type'           => $request->type,
                'quantite'       => $request->quantite,
                'stock_avant'    => $stock_avant,
                'stock_apres'    => $stock_apres,
                'motif'          => $request->motif,
                'reference_doc'  => $request->reference_doc,
                'fournisseur_id' => $request->type === 'entree' ? $request->fournisseur_id : null,
                'client_id'      => $request->type === 'sortie' ? $request->client_id : null,
            ]);
        });

        return redirect()->route('mouvements.index')->with('success', 'Mouvement enregistré avec succès !');
    }

    public function destroy(Mouvement $mouvement)
    {
        $mouvement->delete();
    
        return redirect()->route('mouvements.index')
            ->with('success', 'Mouvement supprimé de l\'historique.');
    }
}