<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'facture_id', 'montant', 'date_paiement', 'mode_paiement', 'notes', 'user_id',
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getModePaiementLabelAttribute()
    {
        return match ($this->mode_paiement) {
            'especes'      => 'Espèces',
            'virement'     => 'Virement',
            'cheque'       => 'Chèque',
            'mobile_money' => 'Mobile Money',
            'autre'        => 'Autre',
            default        => $this->mode_paiement,
        };
    }
}
