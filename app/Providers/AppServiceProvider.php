<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Produit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ==== À AJOUTER : partage des produits en stock bas avec toutes les vues ====
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $produitsStockBas = Produit::where('quantite_stock', '<', 5)
                ->orderBy('quantite_stock', 'asc')
                ->get();

                $view->with('produitsStockBas', $produitsStockBas);
            }
        });
        // ==== FIN AJOUT ====

        if (\Illuminate\Support\Facades\Schema::hasTable('parametres')) {
            \Illuminate\Support\Facades\View::share('parametres', \App\Models\Parametre::instance());
        } else {
            \Illuminate\Support\Facades\View::share('parametres', new \App\Models\Parametre([
                'nom_societe' => 'Inventix',
            ]));
        }

        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
