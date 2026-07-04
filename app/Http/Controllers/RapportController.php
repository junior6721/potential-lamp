<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    /**
     * Calcule toutes les statistiques du mois en cours.
     * Réutilisée par index() (affichage écran) et pdf() (export PDF)
     * pour garantir que les deux affichent exactement les mêmes chiffres.
     */
    private function calculerStatsMois()
    {
        $debutMois = Carbon::now()->startOfMonth();
        $finMois   = Carbon::now()->endOfMonth();

        // Mouvements du mois en cours uniquement
        $mouvementsMois = Mouvement::whereBetween('created_at', [$debutMois, $finMois])->get();

        $totalEntrees = $mouvementsMois->where('type', 'entree')->sum('quantite');
        $totalSorties = $mouvementsMois->where('type', 'sortie')->sum('quantite');
        $nombreMouvements = $mouvementsMois->count();

        // Valeur du stock actuel = somme(quantité produit * prix unitaire) sur tous les produits
        $produits = Produit::all();
        $valeurStock = $produits->sum(function ($produit) {
            return $produit->quantite * $produit->prix;
        });

        $quantiteTotaleStock = $produits->sum('quantite');

        return [
            'mois'                  => $debutMois->locale('fr')->isoFormat('MMMM YYYY'),
            'debutMois'             => $debutMois,
            'finMois'               => $finMois,
            'totalEntrees'          => $totalEntrees,
            'totalSorties'          => $totalSorties,
            'nombreMouvements'      => $nombreMouvements,
            'valeurStock'           => $valeurStock,
            'quantiteTotaleStock'   => $quantiteTotaleStock,
            'nombreProduits'        => $produits->count(),
        ];
    }

    /**
     * Affiche le rapport à l'écran
     */
    public function index()
    {
        $stats = $this->calculerStatsMois();

        return view('rapports.index', compact('stats'));
    }

    /**
     * Génère le même rapport en PDF
     */
    public function pdf()
    {
        $stats = $this->calculerStatsMois();

        $pdf = Pdf::loadView('pdf.rapport', compact('stats'))
            ->setPaper('a4', 'portrait');

        $nomFichier = 'rapport-mensuel-' . Carbon::now()->format('Y-m') . '.pdf';

        return $pdf->download($nomFichier);
    }
}
