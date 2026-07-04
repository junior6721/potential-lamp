@extends('layouts.app')
@section('title', 'Comptage — ' . $inventaire->numero)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">Comptage : {{ $inventaire->numero }}</h2>
        <p class="text-muted mb-0">Saisissez la quantité réellement comptée pour chaque produit.</p>
    </div>
    <a href="{{ route('inventaires.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<form method="POST" action="{{ route('inventaires.enregistrer', $inventaire) }}">
    @csrf

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Référence</th>
                        <th>Stock système</th>
                        <th style="width:160px;">Quantité comptée *</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventaire->lignes as $ligne)
                        <tr>
                            <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                            <td>{{ $ligne->produit->reference ?? '—' }}</td>
                            <td>{{ $ligne->quantite_systeme }} {{ $ligne->produit->unite ?? '' }}</td>
                            <td>
                                <input type="number" name="comptage[{{ $ligne->id }}]" class="form-control form-control-sm"
                                       min="0" value="{{ $ligne->quantite_comptee ?? $ligne->quantite_systeme }}" required>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Valider l'inventaire
        </button>
        <a href="{{ route('inventaires.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>

@endsection