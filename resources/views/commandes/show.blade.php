@extends('layouts.app')
@section('title', 'Commande ' . $commande->numero)
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-0">{{ $commande->numero }}</h2>
        <p class="text-muted mb-0">
            Créée le {{ $commande->created_at->format('d/m/Y à H:i') }}
            @if($commande->user) par {{ $commande->user->name }} @endif
        </p>
    </div>
    
    <div class="d-flex gap-2">
        @if($commande->statut === 'en_attente' && !$commande->facture)
            <a href="{{ route('commandes.edit', $commande) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        @endif
        
        @if($commande->statut === 'recue_livree')
            @if($commande->facture)
                <a href="{{ route('factures.show', $commande->facture) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-receipt"></i> Voir la facture
                </a>
            @else
                <form action="{{ route('commandes.facture.generer', $commande) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-receipt"></i> Générer la facture
                    </button>
                </form>
            @endif
        @endif
        <a href="{{ route('commandes.pdf', $commande) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>


</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Type</div>
                <div class="fw-semibold">
                    @if($commande->type === 'fournisseur')
                        <i class="bi bi-truck"></i> Commande Fournisseur
                    @else
                        <i class="bi bi-person-badge"></i> Commande Client
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">{{ $commande->type === 'fournisseur' ? 'Fournisseur' : 'Client' }}</div>
                <div class="fw-semibold">{{ $commande->tiers?->societe ?: $commande->tiers?->contact ?: '—' }}</div>                @if($commande->tiers && $commande->tiers->contact)
                    <div class="text-muted small">{{ $commande->tiers->contact }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Statut</div>
                <form method="POST" action="{{ route('commandes.statut', $commande) }}">
                    @csrf
                    @method('PATCH')
                    <select name="statut" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmee" {{ $commande->statut == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                        <option value="recue_livree" {{ $commande->statut == 'recue_livree' ? 'selected' : '' }}>
                            {{ $commande->type === 'fournisseur' ? 'Reçue' : 'Livrée' }}
                        </option>
                        <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th class="text-end">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                        <td>{{ $ligne->quantite }}</td>
                        <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                        <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="text-end fw-bold">{{ number_format($commande->total, 0, ',', ' ') }}</td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>

@if($commande->notes)
    <div class="card mt-3">
        <div class="card-body">
            <div class="text-muted small mb-1">Notes</div>
            <div>{{ $commande->notes }}</div>
        </div>
    </div>
@endif

@endsection
