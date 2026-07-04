<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;

class ProduitController extends Controller {

    public function index(Request $request) {
        $query = Produit::with('categorie');
        if ($request->search) {
            $query->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('reference', 'like', "%{$request->search}%");
        }
        if ($request->categorie_id) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->alerte) {
            $query->whereColumn('quantite_stock', '<=', 'stock_minimum');
        }
        $produits    = $query->latest()->paginate(15);
        $categories  = Categorie::all();
        return view('produits.index', compact('produits', 'categories'));
    }

    public function create() {
        $categories = Categorie::all();
        return view('produits.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'reference'     => 'required|string|unique:produits',
            'prix_achat'    => 'required|numeric|min:0',
            'prix_vente'    => 'required|numeric|min:0',
            'quantite_stock'=> 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'unite'         => 'required|string|max:50',
            'categorie_id'  => 'required|exists:categories,id',
        ]);
        Produit::create($request->all());
        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès !');
    }

    public function show(Produit $produit) {
        $mouvements = $produit->mouvements()->with('user')->latest()->paginate(10);
        return view('produits.show', compact('produit', 'mouvements'));
    }

    public function edit(Produit $produit) {
        $categories = Categorie::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit) {
        $request->validate([
            'nom'           => 'required|string|max:255',
            'reference'     => 'required|string|unique:produits,reference,' . $produit->id,
            'prix_achat'    => 'required|numeric|min:0',
            'prix_vente'    => 'required|numeric|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'unite'         => 'required|string|max:50',
            'categorie_id'  => 'required|exists:categories,id',
        ]);
        $produit->update($request->except('quantite_stock'));
        return redirect()->route('produits.index')->with('success', 'Produit mis à jour !');
    }

    public function destroy(Produit $produit) {
        $produit->update(['actif' => false]);
        return redirect()->route('produits.index')->with('success', 'Produit désactivé.');
    }
}
