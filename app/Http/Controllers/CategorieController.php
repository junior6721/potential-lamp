<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller {

    public function index() {
        $categories = Categorie::withCount('produits')->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
        ]);
        Categorie::create($request->only('nom', 'description'));
        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès !');
    }

    public function edit(Categorie $categorie) {
        return view('categories.edit', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie) {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
            'description' => 'nullable|string|max:500',
        ]);
        $categorie->update($request->only('nom', 'description'));
        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour !');
    }

    public function destroy(Categorie $categorie) {
        if ($categorie->produits()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Impossible : cette catégorie contient des produits.');
        }
        $categorie->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
    }
}
