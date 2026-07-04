<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'numero', 'commande_id', 'montant_total', 'date_facture', 'user_id',
    ];

    protected $casts = [
        'date_facture' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    /**
     * Montant total déjà payé (somme de tous les paiements)
     */
    public function getMontantPayeAttribute()
    {
        return $this->paiements->sum('montant');
    }

    /**
     * Montant restant à payer
     */
    public function getResteAPayerAttribute()
    {
        return $this->montant_total - $this->montant_paye;
    }

    /**
     * Statut de paiement calculé automatiquement :
     * impayee / partiellement_payee / payee
     */
    public function getStatutPaiementAttribute()
    {
        if ($this->montant_paye <= 0) {
            return 'impayee';
        }
        if ($this->montant_paye >= $this->montant_total) {
            return 'payee';
        }
        return 'partiellement_payee';
    }

    public function getStatutPaiementLabelAttribute()
    {
        return match ($this->statut_paiement) {
            'impayee'             => 'Impayée',
            'partiellement_payee' => 'Partiellement payée',
            'payee'                => 'Payée',
        };
    }
}
