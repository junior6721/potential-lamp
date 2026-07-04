@extends('layouts.app')
@section('title', 'Factures')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Factures</h2>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Statut de paiement</label>
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <option value="impayee" {{ request('statut') == 'impayee' ? 'selected' : '' }}>Impayée</option>
                    <option value="partiellement_payee" {{ request('statut') == 'partiellement_payee' ? 'selected' : '' }}>Partiellement payée</option>
                    <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary btn-sm w-100">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>N° Facture</th>
                    <th>Commande</th>
                    <th>Tiers</th>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Payé</th>
                    <th class="text-end">Reste dû</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $facture)
                    <tr>
                        <td class="fw-semibold">{{ $facture->numero }}</td>
                        <td>{{ $facture->commande->numero }}</td>
                        <td>{{ $facture->commande->tiers?->societe ?: $facture->commande->tiers?->contact ?: '—' }}</td>
                        <td>{{ $facture->date_facture->format('d/m/Y') }}</td>
                        <td class="text-end">{{ number_format($facture->montant_total, 0, ',', ' ') }}</td>
                        <td class="text-end text-success">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</td>
                        <td class="text-end {{ $facture->reste_a_payer > 0 ? 'text-danger' : '' }}">
                            {{ number_format($facture->reste_a_payer, 0, ',', ' ') }}
                        </td>
                        <td>
                            @php
                                $couleurs = [
                                    'impayee'             => 'badge-sortie',
                                    'partiellement_payee' => 'badge-alerte',
                                    'payee'               => 'badge-ok',
                                ];
                            @endphp
                            <span class="{{ $couleurs[$facture->statut_paiement] }}">{{ $facture->statut_paiement_label }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('factures.show', $facture) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('factures.pdf', $facture) }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            Aucune facture pour l'instant. Les factures se génèrent depuis une commande "Reçue/Livrée".
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@if(method_exists($factures, 'links'))
<div class="mt-3">
    {{ $factures->links() }}
</div>
@endif

@endsection
