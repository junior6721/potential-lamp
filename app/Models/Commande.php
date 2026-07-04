<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable = [
        'numero', 'type', 'fournisseur_id', 'client_id',
        'statut', 'date_commande', 'notes', 'user_id',
    ];

    protected $casts = [
        'date_commande' => 'date',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lignes()
    {
        return $this->hasMany(CommandeLigne::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    /**
     * Calcule le montant total de la commande
     * (somme de quantité * prix_unitaire pour chaque ligne)
     */
    public function getTotalAttribute()
    {
        return $this->lignes->sum(function ($ligne) {
            return $ligne->quantite * $ligne->prix_unitaire;
        });
    }

    /**
     * Libellé lisible du statut, pour l'affichage
     */
    public function getStatutLabelAttribute()
    {
        return match ($this->statut) {
            'en_attente'   => 'En attente',
            'confirmee'    => 'Confirmée',
            'recue_livree' => $this->type === 'fournisseur' ? 'Reçue' : 'Livrée',
            'annulee'      => 'Annulée',
            default        => $this->statut,
        };
    }

    /**
     * Le tiers concerné (fournisseur ou client), peu importe le type
     */
    public function getTiersAttribute()
    {
        return $this->type === 'fournisseur' ? $this->fournisseur : $this->client;
    }
}
