<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {

        // Créer l'admin
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@Inventix.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // Créer un employé
        User::create([
            'name'     => 'Employé Test',
            'email'    => 'employe@Inventix.com',
            'password' => Hash::make('employe123'),
            'role'     => 'employe',
        ]);

        // Catégories
        $cats = ['Électronique', 'Alimentaire', 'Boissons', 'Papeterie', 'Quincaillerie'];
        foreach ($cats as $nom) {
            Categorie::create(['nom' => $nom, 'description' => 'Catégorie ' . $nom]);
        }

        // Produits exemples
        $produits = [
            ['nom' => 'Écran 24 pouces',   'reference' => 'ECR-001', 'prix_achat' => 85000,  'prix_vente' => 110000, 'quantite_stock' => 15, 'stock_minimum' => 3,  'unite' => 'unité',   'categorie_id' => 1],
            ['nom' => 'Clavier USB',        'reference' => 'CLA-001', 'prix_achat' => 8000,   'prix_vente' => 12000,  'quantite_stock' => 30, 'stock_minimum' => 5,  'unite' => 'unité',   'categorie_id' => 1],
            ['nom' => 'Riz local 25kg',     'reference' => 'RIZ-001', 'prix_achat' => 12000,  'prix_vente' => 15000,  'quantite_stock' => 4,  'stock_minimum' => 10, 'unite' => 'sac',     'categorie_id' => 2],
            ['nom' => 'Eau minérale 1.5L',  'reference' => 'EAU-001', 'prix_achat' => 200,    'prix_vente' => 350,    'quantite_stock' => 200,'stock_minimum' => 50, 'unite' => 'bouteille','categorie_id' => 3],
            ['nom' => 'Stylos BIC x10',     'reference' => 'STY-001', 'prix_achat' => 500,    'prix_vente' => 800,    'quantite_stock' => 3,  'stock_minimum' => 20, 'unite' => 'boîte',   'categorie_id' => 4],
        ];

        foreach ($produits as $p) {
            Produit::create($p);
        }
    }
}
