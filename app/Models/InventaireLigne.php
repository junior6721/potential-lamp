<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventaireLigne extends Model
{
    protected $fillable = ['inventaire_id', 'produit_id', 'quantite_systeme', 'quantite_comptee'];

    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function getEcartAttribute()
    {
        if (is_null($this->quantite_comptee)) {
            return 0;
        }
        return $this->quantite_comptee - $this->quantite_systeme;
    }
}