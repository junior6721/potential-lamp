<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Mouvement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller {

    // ── 1. État du stock complet ──
    public function stock() {
        $produits = Produit::with('categorie')
            ->where('actif', true)
            ->orderBy('categorie_id')
            ->orderBy('nom')
            ->get();

        $valeur_totale = $produits->sum(fn($p) => $p->quantite_stock * $p->prix_achat);
        $ruptures      = $produits->filter(fn($p) => $p->enRuptureDeStock())->count();

        $pdf = Pdf::loadView('pdf.stock', compact('produits', 'valeur_totale', 'ruptures'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('etat-stock-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── 2. Historique des mouvements ──
    public function mouvements(Request $request) {
        $query = Mouvement::with(['produit', 'user']);

        if ($request->type) {
            $query->where('type', $request->type);
        }
        if ($request->date_debut) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->date_fin) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements  = $query->latest()->get();
        $date_debut  = $request->date_debut ?? $mouvements->last()?->created_at?->format('d/m/Y') ?? '-';
        $date_fin    = $request->date_fin   ?? now()->format('d/m/Y');
        $total_entrees = $mouvements->where('type', 'entree')->sum('quantite');
        $total_sorties = $mouvements->where('type', 'sortie')->sum('quantite');

        $pdf = Pdf::loadView('pdf.mouvements', compact(
            'mouvements', 'date_debut', 'date_fin', 'total_entrees', 'total_sorties'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('mouvements-' . now()->format('Y-m-d') . '.pdf');
    }

    // ── 3. Bon de mouvement unique ──
    public function bon(Mouvement $mouvement) {
        $mouvement->load(['produit.categorie', 'user']);

        $pdf = Pdf::loadView('pdf.bon', compact('mouvement'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('bon-' . $mouvement->type . '-' . $mouvement->id . '.pdf');
    }
}
