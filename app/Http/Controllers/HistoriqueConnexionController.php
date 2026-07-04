<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueConnexion;

class HistoriqueConnexionController extends Controller
{
    public function index()
    {
        $connexions = HistoriqueConnexion::with('user')->latest('connecte_a')->paginate(20);
        return view('historique-connexions.index', compact('connexions'));
    }

    public function destroy()
    {
        \App\Models\HistoriqueConnexion::truncate();
        return redirect()->route('historique-connexions.index')
            ->with('success', 'Historique des connexions vidé !');
    }
}