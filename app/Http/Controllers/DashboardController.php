<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Mouvement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {

        // ── STATS ──
        $stats = [
            'total_produits'   => Produit::where('actif', true)->count(),
            'total_categories' => Categorie::count(),
            'total_mouvements' => Mouvement::count(),
            'total_users'      => User::count(),
            'ruptures'         => Produit::whereColumn('quantite_stock', '<=', 'stock_minimum')->count(),
            'valeur_stock'     => Produit::where('actif', true)
                                    ->selectRaw('SUM(quantite_stock * prix_achat) as total')
                                    ->value('total') ?? 0,
        ];

        // ── ALERTES ──
        $alertes = Produit::with('categorie')
            ->whereColumn('quantite_stock', '<=', 'stock_minimum')
            ->where('actif', true)
            ->latest()->take(5)->get();

        // ── DERNIERS MOUVEMENTS ──
        $derniers_mouvements = Mouvement::with(['produit', 'user'])
            ->latest()->take(8)->get();

        // ── GRAPHIQUE 1 : Entrées vs Sorties sur 7 jours ──
        $labels  = [];
        $entrees = [];
        $sorties = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::now()->subDays($i);
            $labels[] = $date->format('D d/m');

            $entrees[] = (int) Mouvement::where('type', 'entree')
                ->whereDate('created_at', $date->toDateString())
                ->sum('quantite');

            $sorties[] = (int) Mouvement::where('type', 'sortie')
                ->whereDate('created_at', $date->toDateString())
                ->sum('quantite');
        }

        $graphique_mouvements = [
            'labels'  => $labels,
            'entrees' => $entrees,
            'sorties' => $sorties,
        ];

        // ── GRAPHIQUE 2 : Top 5 produits les plus mouvementés ──
        $top_produits = Mouvement::select('produit_id', DB::raw('SUM(quantite) as total_mouvements'))
            ->with('produit')
            ->groupBy('produit_id')
            ->orderByDesc('total_mouvements')
            ->take(5)
            ->get();

        $graphique_top = [
            'labels' => $top_produits->map(fn($m) => $m->produit->nom ?? 'Inconnu')->toArray(),
            'data'   => $top_produits->map(fn($m) => (int) $m->total_mouvements)->toArray(),
        ];

        return view('dashboard.index', compact(
            'stats',
            'alertes',
            'derniers_mouvements',
            'graphique_mouvements',
            'graphique_top'
        ));
    }
}