<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'societe'   => 'nullable|string|max:255',
            'contact'   => 'required_without:societe|nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'adresse'   => 'nullable|string',
        ]);

        Client::create([
            'societe'   => $request->societe,
            'contact'   => $request->contact,
            'telephone' => $request->telephone,
            'email'     => $request->email,
            'adresse'   => $request->adresse,
            'actif'     => $request->has('actif'),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client ajouté !');
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'societe'   => 'nullable|string|max:255',
            'contact'   => 'required_without:societe|nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:255',
            'adresse'   => 'nullable|string',
        ]);

        $client->update([
            'societe'   => $request->societe,
            'contact'   => $request->contact,
            'telephone' => $request->telephone,
            'email'     => $request->email,
            'adresse'   => $request->adresse,
            'actif'     => $request->has('actif'),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client mis à jour !');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé !');
    }
}