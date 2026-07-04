<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use Illuminate\Http\Request;

class ParametreController extends Controller
{
    public function index()
    {
        $parametre = Parametre::instance();
        return view('parametres.index', compact('parametre'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nom_societe' => 'required|string|max:255',
            'adresse'     => 'nullable|string|max:500',
            'telephone'   => 'nullable|string|max:50',
            'email'       => 'nullable|email|max:255',
            'ifu'         => 'nullable|string|max:100',
            'site_web'    => 'nullable|url|max:255',
            'logo'        => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'cachet'      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $parametre = Parametre::instance();
        $data = $request->only('nom_societe', 'adresse', 'telephone', 'email', 'ifu', 'site_web');

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si existant
            if ($parametre->logo && file_exists(storage_path('app/public/' . $parametre->logo))) {
                unlink(storage_path('app/public/' . $parametre->logo));
            }
            $data['logo'] = $request->file('logo')->store('parametres', 'public');
        }

        if ($request->hasFile('cachet')) {
            if ($parametre->cachet && file_exists(storage_path('app/public/' . $parametre->cachet))) {
                unlink(storage_path('app/public/' . $parametre->cachet));
            }
            $data['cachet'] = $request->file('cachet')->store('parametres', 'public');
        }

        $parametre->update($data);

        return redirect()->route('parametres.index')->with('success', 'Paramètres enregistrés !');
    }
}