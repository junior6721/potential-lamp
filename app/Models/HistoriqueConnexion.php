<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueConnexion extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'adresse_ip', 'connecte_a'];

    protected $casts = [
        'connecte_a' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}