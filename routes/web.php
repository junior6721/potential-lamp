<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\InventaireController;
use App\Http\Controllers\HistoriqueConnexionController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\ParametreController;


    Route::get('/', fn() => redirect()->route('dashboard'));

    // Auth
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

    // Routes protégées
    Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('produits',   ProduitController::class);
    Route::resource('categories', CategorieController::class)->parameters([
        'categories' => 'categorie'
    ]);
    Route::resource('mouvements', MouvementController::class)->only(['index','create','store']);

    // Exports PDF
    Route::get('/pdf/stock',           [PdfController::class, 'stock'])->name('pdf.stock');
    Route::get('/pdf/mouvements',      [PdfController::class, 'mouvements'])->name('pdf.mouvements');
    Route::get('/pdf/bon/{mouvement}', [PdfController::class, 'bon'])->name('pdf.bon');

    // Profil
    Route::get('/profil',                  [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil/info',             [ProfilController::class, 'updateInfo'])->name('profil.updateInfo');
    Route::put('/profil/password',         [ProfilController::class, 'updatePassword'])->name('profil.updatePassword');

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::delete('/mouvements/{mouvement}', [MouvementController::class, 'destroy'])->name('mouvements.destroy');
        Route::delete('/historique-connexions', [HistoriqueConnexionController::class, 'destroy'])->name('historique-connexions.destroy');
    });
    // Rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/pdf', [RapportController::class, 'pdf'])->name('rapports.pdf');

    // Consultation ET gestion clients : accessible à tous les utilisateurs connectés
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Consultation fournisseurs : accessible à tous
    Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');

    // Gestion fournisseurs : Admin uniquement
    Route::middleware('role:admin')->group(function () {
        Route::get('/fournisseurs/create', [FournisseurController::class, 'create'])->name('fournisseurs.create');
        Route::post('/fournisseurs', [FournisseurController::class, 'store'])->name('fournisseurs.store');
        Route::get('/fournisseurs/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
        Route::put('/fournisseurs/{fournisseur}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
        Route::delete('/fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
    });

    //Commandes
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/create', [CommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
    Route::patch('/commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('commandes.statut');
    Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
    Route::get('/commandes/{commande}/pdf', [CommandeController::class, 'pdf'])->name('commandes.pdf');
    Route::get('/commandes/{commande}/edit', [CommandeController::class, 'edit'])->name('commandes.edit');
    Route::put('/commandes/{commande}', [CommandeController::class, 'update'])->name('commandes.update');

    // Factures - Paiements
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{facture}/pdf', [FactureController::class, 'pdf'])->name('factures.pdf');
    Route::post('/commandes/{commande}/generer-facture', [FactureController::class, 'generer'])->name('commandes.facture.generer');
    
    Route::post('/factures/{facture}/paiements', [FactureController::class, 'ajouterPaiement'])->name('factures.paiements.store');
    Route::delete('/paiements/{paiement}', [FactureController::class, 'supprimerPaiement'])->name('paiements.destroy');

    // Inventaires
    Route::get('/inventaires', [InventaireController::class, 'index'])->name('inventaires.index');
    Route::post('/inventaires', [InventaireController::class, 'create'])->name('inventaires.create');
    Route::get('/inventaires/{inventaire}/compter', [InventaireController::class, 'compter'])->name('inventaires.compter');
    Route::post('/inventaires/{inventaire}/comptage', [InventaireController::class, 'enregistrerComptage'])->name('inventaires.enregistrer');
    Route::get('/inventaires/{inventaire}', [InventaireController::class, 'show'])->name('inventaires.show');
    
    // Historique-Connexion
    Route::middleware('role:admin')->group(function () {
        Route::get('/historique-connexions', [HistoriqueConnexionController::class, 'index'])->name('historique-connexions.index');
    });

    //Recherche
    Route::get('/recherche', [App\Http\Controllers\RechercheController::class, 'index'])->name('recherche');

    //Paramètre
    Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres.index');
    Route::put('/parametres', [ParametreController::class, 'update'])->name('parametres.update');

});
