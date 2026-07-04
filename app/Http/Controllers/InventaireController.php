<?php

namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventaireLigne;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventaireController extends Controller
{
    public function index()
    {
        $inventaires = Inventaire::with('user', 'lignes')->latest()->paginate(10);
        return view('inventaires.index', compact('inventaires'));
    }

    public function create()
    {
        $inventaire = null;

        DB::transaction(function () use (&$inventaire) {
            $inventaire = Inventaire::create([
                'numero'  => $this->genererNumero(),
                'user_id' => auth()->id(),
                'statut'  => 'en_cours',
            ]);

            $produits = Produit::where('actif', true)->get();

            foreach ($produits as $produit) {
                InventaireLigne::create([
                    'inventaire_id'    => $inventaire->id,
                    'produit_id'       => $produit->id,
                    'quantite_systeme' => $produit->quantite_stock,
                    'quantite_comptee' => null,
                ]);
            }
        });

        return redirect()->route('inventaires.compter', $inventaire);
    }

    public function compter(Inventaire $inventaire)
    {
        if ($inventaire->statut === 'termine') {
            return redirect()->route('inventaires.show', $inventaire);
        }

        $inventaire->load('lignes.produit');
        return view('inventaires.compter', compact('inventaire'));
    }

    public function enregistrerComptage(Request $request, Inventaire $inventaire)
    {
        $request->validate([
            'comptage'   => 'required|array',
            'comptage.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $inventaire) {
            foreach ($request->comptage as $ligneId => $quantite) {
                InventaireLigne::where('id', $ligneId)
                    ->where('inventaire_id', $inventaire->id)
                    ->update(['quantite_comptee' => $quantite]);
            }

            $inventaire->update(['statut' => 'termine']);
        });

        return redirect()->route('inventaires.show', $inventaire)
            ->with('success', 'Inventaire enregistré avec succès !');
    }

    public function show(Inventaire $inventaire)
    {
        $inventaire->load('lignes.produit', 'user');
        return view('inventaires.show', compact('inventaire'));
    }

    private function genererNumero(): string
    {
        $annee = now()->year;
        $dernier = Inventaire::where('numero', 'like', "INV-{$annee}-%")
            ->orderBy('id', 'desc')
            ->first();

        $prochain = $dernier ? ((int) substr($dernier->numero, -4)) + 1 : 1;

        return sprintf('INV-%s-%04d', $annee, $prochain);
    }
}