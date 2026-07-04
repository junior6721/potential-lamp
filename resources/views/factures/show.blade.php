@extends('layouts.app')
@section('title', 'Facture ' . $facture->numero)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">{{ $facture->numero }}</h2>
        <p class="text-muted mb-0">
            Générée le {{ $facture->created_at->format('d/m/Y à H:i') }}
            — Commande liée : {{ $facture->commande->numero }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('factures.pdf', $facture) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-num">{{ number_format($facture->montant_total, 0, ',', ' ') }}</div>
            <div class="stat-label">Montant total</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-num text-success">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</div>
            <div class="stat-label">Déjà payé</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-num {{ $facture->reste_a_payer > 0 ? 'text-danger' : 'text-success' }}">
                {{ number_format($facture->reste_a_payer, 0, ',', ' ') }}
            </div>
            <div class="stat-label">Reste à payer</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            @php
                $couleurs = [
                    'impayee'             => 'badge-sortie',
                    'partiellement_payee' => 'badge-alerte',
                    'payee'               => 'badge-ok',
                ];
            @endphp
            <span class="{{ $couleurs[$facture->statut_paiement] }}" style="font-size:14px;">
                {{ $facture->statut_paiement_label }}
            </span>
            <div class="stat-label mt-1">Statut</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Détail de la commande facturée -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Tiers concerné</div>
            <div class="card-body">
            <strong>{{ $facture->commande->tiers?->societe ?: $facture->commande->tiers?->contact ?: '—' }}</strong><br>                @if($facture->commande->tiers)
                    {{ $facture->commande->tiers->contact ?? '' }}<br>
                    {{ $facture->commande->tiers->telephone ?? '' }}<br>
                    {{ $facture->commande->tiers->email ?? '' }}
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">Détail des produits facturés</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th class="text-end">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facture->commande->lignes as $ligne)
                            <tr>
                                <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                                <td>{{ $ligne->quantite }}</td>
                                <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Paiements -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Historique des paiements</div>
            <div class="card-body">
                @forelse($facture->paiements as $paiement)
                    <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--border) !important;">
                        <div>
                            <div class="fw-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }}</div>
                            <div class="text-muted small">
                                {{ $paiement->date_paiement->format('d/m/Y') }} — {{ $paiement->mode_paiement_label }}
                                @if($paiement->notes) <br>{{ $paiement->notes }} @endif
                            </div>
                        </div>
                        <form action="{{ route('paiements.destroy', $paiement) }}" method="POST"
                              onsubmit="return confirm('Supprimer ce paiement ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucun paiement enregistré pour l'instant.</p>
                @endforelse
            </div>
        </div>

        @if($facture->reste_a_payer > 0)
            <div class="card mt-3">
                <div class="card-header">Enregistrer un paiement</div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('factures.paiements.store', $facture) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Montant (reste dû : {{ number_format($facture->reste_a_payer, 0, ',', ' ') }})</label>
                            <input type="number" name="montant" class="form-control" step="0.01"
                                   max="{{ $facture->reste_a_payer }}" value="{{ $facture->reste_a_payer }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date du paiement</label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mode de paiement</label>
                            <select name="mode_paiement" class="form-select" required>
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement</option>
                                <option value="cheque">Chèque</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (optionnel)</label>
                            <input type="text" name="notes" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Enregistrer le paiement
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-success mt-3 mb-0">
                <i class="bi bi-check-circle"></i> Cette facture est entièrement payée.
            </div>
        @endif
    </div>
</div>

@endsection
