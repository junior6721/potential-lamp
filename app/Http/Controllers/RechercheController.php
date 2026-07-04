<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Fournisseur;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Facture;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $q = strtolower(trim($request->get('q', '')));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $resultats = [];

        // Mots-clés qui déclenchent chaque section
        $sections = [
            'produit'    => ['produit', 'produits', 'article', 'stock'],
            'fournisseur'=> ['fournisseur', 'fournisseurs', 'fourniss'],
            'client'     => ['client', 'clients'],
            'commande'   => ['commande', 'commandes', 'bon', 'bons'],
            'facture'    => ['facture', 'factures'],
            'categorie'  => ['categorie', 'catégorie', 'categories', 'catégories'],
        ];

        $charger = [];
        foreach ($sections as $section => $mots) {
            foreach ($mots as $mot) {
                if (str_contains($mot, $q) || str_contains($q, $mot)) {
                    $charger[] = $section;
                    break;
                }
            }
        }

        // Si aucun mot-clé de section ne correspond, on cherche quand même dans les noms
        if (empty($charger)) {
            // Recherche classique dans les noms
            $produits = Produit::where('nom', 'like', "%{$q}%")
                ->orWhere('reference', 'like', "%{$q}%")->limit(5)->get();
            foreach ($produits as $p) {
                $resultats[] = ['categorie'=>'Produits','icone'=>'bi-box',
                    'label'=>$p->nom,'sous'=>$p->reference,
                    'url'=>route('produits.index').'?search='.urlencode($p->nom)];
            }

            $fournisseurs = Fournisseur::where('societe','like',"%{$q}%")
                ->orWhere('contact','like',"%{$q}%")->limit(3)->get();
            foreach ($fournisseurs as $f) {
                $resultats[] = ['categorie'=>'Fournisseurs','icone'=>'bi-truck',
                    'label'=>$f->societe ?? $f->contact,'sous'=>$f->telephone ?? '',
                    'url'=>route('fournisseurs.index')];
            }

            $clients = Client::where('societe','like',"%{$q}%")
                ->orWhere('contact','like',"%{$q}%")->limit(3)->get();
            foreach ($clients as $c) {
                $resultats[] = ['categorie'=>'Clients','icone'=>'bi-person-badge',
                    'label'=>$c->societe ?? $c->contact,'sous'=>$c->telephone ?? '',
                    'url'=>route('clients.index')];
            }

            return response()->json($resultats);
        }

        // Charger tous les éléments des sections correspondantes
        if (in_array('produit', $charger)) {
            $produits = Produit::where('actif', true)->limit(10)->get();
            foreach ($produits as $p) {
                $resultats[] = ['categorie'=>'Produits','icone'=>'bi-box',
                    'label'=>$p->nom,
                    'sous'=>'Réf: '.$p->reference.' — Stock: '.$p->quantite_stock,
                    'url'=>route('produits.index')];
            }
        }

        if (in_array('fournisseur', $charger)) {
            $fournisseurs = Fournisseur::where('actif', true)->limit(10)->get();
            foreach ($fournisseurs as $f) {
                $resultats[] = ['categorie'=>'Fournisseurs','icone'=>'bi-truck',
                    'label'=>$f->societe ?? $f->contact,
                    'sous'=>$f->telephone ?? $f->email ?? '',
                    'url'=>route('fournisseurs.index')];
            }
        }

        if (in_array('client', $charger)) {
            $clients = Client::where('actif', true)->limit(10)->get();
            foreach ($clients as $c) {
                $resultats[] = ['categorie'=>'Clients','icone'=>'bi-person-badge',
                    'label'=>$c->societe ?? $c->contact,
                    'sous'=>$c->telephone ?? $c->email ?? '',
                    'url'=>route('clients.index')];
            }
        }

        if (in_array('commande', $charger)) {
            $commandes = Commande::latest()->limit(8)->get();
            foreach ($commandes as $cmd) {
                $resultats[] = ['categorie'=>'Commandes','icone'=>'bi-cart',
                    'label'=>$cmd->numero,
                    'sous'=>ucfirst(str_replace('_',' ',$cmd->statut)),
                    'url'=>route('commandes.show', $cmd)];
            }
        }

        if (in_array('facture', $charger)) {
            $factures = Facture::latest()->limit(8)->get();
            foreach ($factures as $fac) {
                $resultats[] = ['categorie'=>'Factures','icone'=>'bi-receipt',
                    'label'=>$fac->numero,
                    'sous'=>number_format($fac->montant_total,0,',',' ').' FCFA',
                    'url'=>route('factures.show', $fac)];
            }
        }

        if (in_array('categorie', $charger)) {
            $cats = \App\Models\Categorie::limit(10)->get();
            foreach ($cats as $cat) {
                $resultats[] = ['categorie'=>'Catégories','icone'=>'bi-tags',
                    'label'=>$cat->nom,
                    'sous'=>'',
                    'url'=>route('categories.index')];
            }
        }

        // Si résultats vides malgré section reconnue, proposer le lien direct
        if (empty($resultats)) {
            if (in_array('produit', $charger))
                $resultats[] = ['categorie'=>'Produits','icone'=>'bi-box',
                    'label'=>'Voir tous les produits','sous'=>'','url'=>route('produits.index')];
            if (in_array('fournisseur', $charger))
                $resultats[] = ['categorie'=>'Fournisseurs','icone'=>'bi-truck',
                    'label'=>'Voir tous les fournisseurs','sous'=>'','url'=>route('fournisseurs.index')];
            if (in_array('client', $charger))
                $resultats[] = ['categorie'=>'Clients','icone'=>'bi-person-badge',
                    'label'=>'Voir tous les clients','sous'=>'','url'=>route('clients.index')];
        }

        return response()->json($resultats);
    }
}