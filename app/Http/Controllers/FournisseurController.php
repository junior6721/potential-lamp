<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::latest()->paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'societe'   => 'required|string|max:255',
            'contact'   => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'adresse'   => 'nullable|string',
        ]);

        Fournisseur::create([
            'societe'   => $request->societe,
            'contact'   => $request->contact,
            'telephone' => $request->telephone,
            'email'     => $request->email,
            'adresse'   => $request->adresse,
            'actif'     => $request->has('actif'),
        ]);

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté !');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'societe'   => 'required|string|max:255',
            'contact'   => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'adresse'   => 'nullable|string',
        ]);

        $fournisseur->update([
            'societe'   => $request->societe,
            'contact'   => $request->contact,
            'telephone' => $request->telephone,
            'email'     => $request->email,
            'adresse'   => $request->adresse,
            'actif'     => $request->has('actif'),
        ]);

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur mis à jour !');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé !');
    }
}