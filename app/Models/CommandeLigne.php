<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandeLigne extends Model
{
    protected $fillable = [
        'commande_id', 'produit_id', 'quantite', 'prix_unitaire',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function getSousTotalAttribute()
    {
        return $this->quantite * $this->prix_unitaire;
    }
}
