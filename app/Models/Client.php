<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'societe', 'contact', 'telephone', 'email', 'adresse', 'actif',
    ];

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }
}