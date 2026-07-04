@extends('layouts.app')
@section('title', 'Résultat — ' . $inventaire->numero)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">{{ $inventaire->numero }}</h2>
        <p class="text-muted mb-0">
            Réalisé le {{ $inventaire->created_at->format('d/m/Y à H:i') }}
            @if($inventaire->user) par {{ $inventaire->user->name }} @endif
        </p>
    </div>
    <a href="{{ route('inventaires.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Stock système</th>
                    <th>Stock compté</th>
                    <th class="text-end">Écart</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventaire->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                        <td>{{ $ligne->quantite_systeme }}</td>
                        <td>{{ $ligne->quantite_comptee }}</td>
                        <td class="text-end">
                            @if($ligne->ecart === 0)
                                <span class="text-success">0</span>
                            @elseif($ligne->ecart > 0)
                                <span class="text-success fw-bold">+{{ $ligne->ecart }}</span>
                            @else
                                <span class="text-danger fw-bold">{{ $ligne->ecart }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>

@if($inventaire->nombre_ecarts > 0)
    <div class="alert alert-warning mt-3">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ $inventaire->nombre_ecarts }} écart(s) détecté(s). Le stock système n'a pas été modifié automatiquement —
        si nécessaire, corrigez-le manuellement via un mouvement d'entrée ou de sortie.
    </div>
@else
    <div class="alert alert-success mt-3">
        <i class="bi bi-check-circle me-2"></i>
        Aucun écart détecté, le stock système correspond au comptage physique.
    </div>
@endif

@endsection