@extends('layouts.app')
@section('title', 'Rapport mensuel')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">📊 Rapport mensuel</h2>
        <p class="mb-0 text-capitalize" style="color: #9ca3af;">{{ $stats['mois'] }}</p>
    </div>
    <a href="{{ route('rapports.pdf') }}" class="btn btn-danger">
        📄 Exporter en PDF
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-num text-success">+{{ $stats['totalEntrees'] }}</div>
            <div class="stat-label">Entrées (mois)</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-num text-danger">-{{ $stats['totalSorties'] }}</div>
            <div class="stat-label">Sorties (mois)</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-num">{{ $stats['nombreMouvements'] }}</div>
            <div class="stat-label">Mouvements (mois)</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-num">{{ number_format($stats['valeurStock'], 0, ',', ' ') }}</div>
            <div class="stat-label">Valeur du stock actuel</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">Résumé du stock actuel</h5>
        <table class="table table-rapport mb-0">
            <tbody>
                <tr>
                    <td>Nombre de produits référencés</td>
                    <td class="text-end fw-bold">{{ $stats['nombreProduits'] }}</td>
                </tr>
                <tr>
                    <td>Quantité totale en stock</td>
                    <td class="text-end fw-bold">{{ $stats['quantiteTotaleStock'] }}</td>
                </tr>
                <tr>
                    <td>Valeur totale du stock</td>
                    <td class="text-end fw-bold">{{ number_format($stats['valeurStock'], 0, ',', ' ') }}</td>
                </tr>
                <tr>
                    <td>Solde du mois (entrées - sorties)</td>
                    <td class="text-end fw-bold {{ ($stats['totalEntrees'] - $stats['totalSorties']) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $stats['totalEntrees'] - $stats['totalSorties'] >= 0 ? '+' : '' }}{{ $stats['totalEntrees'] - $stats['totalSorties'] }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style>
    .table-rapport td {
        padding: 12px 8px;
        border-color: var(--border) !important;
        color: var(--text) !important;
        background: transparent !important;
    }
    .table-rapport tr:last-child td {
        border-bottom: none;
    }
</style>
@endsection
