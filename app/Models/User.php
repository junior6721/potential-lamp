<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'actif'];
    protected $hidden   = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'actif'             => 'boolean',
    ];

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isEmploye(): bool {
        return $this->role === 'employe';
    }

    public function mouvements() {
        return $this->hasMany(Mouvement::class);
    }
}
