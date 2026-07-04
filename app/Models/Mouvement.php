<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'produit_id',
        'user_id',
        'type',
        'quantite',
        'stock_avant',
        'stock_apres',
        'motif',
        'reference_doc',
        'fournisseur_id',
        'client_id',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}