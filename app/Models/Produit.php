<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model {
    protected $fillable = [
        'nom', 'reference', 'description',
        'prix_achat', 'prix_vente',
        'quantite_stock', 'stock_minimum',
        'unite', 'categorie_id', 'actif'
    ];

    protected $casts = ['actif' => 'boolean'];

    public function categorie() {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function mouvements() {
        return $this->hasMany(Mouvement::class);
    }

    public function enRuptureDeStock(): bool {
        return $this->quantite_stock <= $this->stock_minimum;
    }
}
