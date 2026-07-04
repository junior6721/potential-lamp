<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller {

    // Afficher la page profil
    public function index() {
        $user        = auth()->user();
        $mouvements  = $user->mouvements()->with('produit')->latest()->take(5)->get();
        $total_mouvements = $user->mouvements()->count();
        $total_entrees    = $user->mouvements()->where('type', 'entree')->count();
        $total_sorties    = $user->mouvements()->where('type', 'sortie')->count();

        return view('profil.index', compact(
            'user', 'mouvements',
            'total_mouvements', 'total_entrees', 'total_sorties'
        ));
    }

    // Mettre à jour les infos
    public function updateInfo(Request $request) {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success_info', 'Informations mises à jour avec succès !');
    }

    // Changer le mot de passe
    public function updatePassword(Request $request) {
        $request->validate([
            'current_password'  => 'required',
            'password'          => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.'])->with('tab', 'password');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_pwd', 'Mot de passe changé avec succès !');
    }
}
