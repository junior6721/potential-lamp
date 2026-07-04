<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametre extends Model
{
    protected $fillable = [
        'nom_societe', 'adresse', 'telephone',
        'email', 'ifu', 'site_web', 'logo', 'cachet',
    ];

    // Récupère toujours la première (et unique) ligne
    public static function instance(): self
    {
        return self::firstOrCreate([], ['nom_societe' => 'Inventix']);
    }
}