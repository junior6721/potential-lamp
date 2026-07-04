<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Commande;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::with(['commande.fournisseur', 'commande.client', 'paiements']);

        $factures = $query->latest()->paginate(15);

        // Filtre par statut de paiement (calculé, donc filtré après récupération)
        if ($request->statut) {
            $factures = $factures->filter(function ($facture) use ($request) {
                return $facture->statut_paiement === $request->statut;
            });
        }

        return view('factures.index', compact('factures'));
    }

    /**
     * Génère une facture à partir d'une commande "Reçue/Livrée"
     */
    public function generer(Commande $commande)
    {
        if ($commande->statut !== 'recue_livree') {
            return redirect()->back()->with('error', 'Seule une commande Reçue/Livrée peut être facturée.');
        }

        if ($commande->facture()->exists()) {
            return redirect()->route('factures.show', $commande->facture)
                ->with('error', 'Cette commande a déjà une facture.');
        }

        $facture = Facture::create([
            'numero'        => $this->genererNumero(),
            'commande_id'   => $commande->id,
            'montant_total' => $commande->total,
            'date_facture'  => now(),
            'user_id'       => auth()->id(),
        ]);

        return redirect()->route('factures.show', $facture)->with('success', 'Facture générée avec succès !');
    }

    public function show(Facture $facture)
    {
        $facture->load(['commande.fournisseur', 'commande.client', 'commande.lignes.produit', 'paiements.user']);
        return view('factures.show', compact('facture'));
    }

    public function ajouterPaiement(Request $request, Facture $facture)
    {
        $request->validate([
            'montant'       => 'required|numeric|min:0.01|max:' . $facture->reste_a_payer,
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|in:especes,virement,cheque,mobile_money,autre',
            'notes'         => 'nullable|string',
        ], [
            'montant.max' => 'Le montant ne peut pas dépasser le reste à payer (:max).',
        ]);

        Paiement::create([
            'facture_id'    => $facture->id,
            'montant'       => $request->montant,
            'date_paiement' => $request->date_paiement,
            'mode_paiement' => $request->mode_paiement,
            'notes'         => $request->notes,
            'user_id'       => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Paiement enregistré !');
    }

    public function supprimerPaiement(Paiement $paiement)
    {
        $facture = $paiement->facture;
        $paiement->delete();
        return redirect()->route('factures.show', $facture)->with('success', 'Paiement supprimé !');
    }

    public function pdf(Facture $facture)
    {
        $facture->load(['commande.fournisseur', 'commande.client', 'commande.lignes.produit', 'paiements']);

        $pdf = Pdf::loadView('pdf.facture', compact('facture'))->setPaper('a4', 'portrait');

        return $pdf->download('facture-' . $facture->numero . '.pdf');
    }

    private function genererNumero(): string
    {
        $annee   = now()->year;
        $dernier = Facture::whereYear('created_at', $annee)->count();
        $prochain = $dernier + 1;

        return sprintf('FACT-%d-%04d', $annee, $prochain);
    }
}
