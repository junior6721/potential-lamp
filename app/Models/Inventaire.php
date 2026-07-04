<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    protected $fillable = ['numero', 'user_id', 'statut'];

    public function lignes()
    {
        return $this->hasMany(InventaireLigne::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreEcartsAttribute()
    {
        return $this->lignes->filter(fn($l) => $l->ecart !== 0)->count();
    }
}