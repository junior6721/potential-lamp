@extends('layouts.app')
@section('title', 'Commandes')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Commandes</h2>
    <a href="{{ route('commandes.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Nouvelle commande
    </a>
</div>

<!-- Filtres -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <option value="fournisseur" {{ request('type') == 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                    <option value="client" {{ request('type') == 'client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous</option>
                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                    <option value="recue_livree" {{ request('statut') == 'recue_livree' ? 'selected' : '' }}>Reçue / Livrée</option>
                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
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
                    <th>N° Commande</th>
                    <th>Type</th>
                    <th>Tiers</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                    <tr>
                        <td class="fw-semibold">{{ $commande->numero }}</td>
                        <td>
                            @if($commande->type === 'fournisseur')
                                <i class="bi bi-truck"></i> Fournisseur
                            @else
                                <i class="bi bi-person-badge"></i> Client
                            @endif
                        </td>
                        <td>{{ $commande->tiers->societe ?? '—' }}</td>
                        <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                        <td>{{ number_format($commande->total, 0, ',', ' ') }}</td>
                        <td>
                            @php
                                $couleurs = [
                                    'en_attente'   => 'badge-alerte',
                                    'confirmee'    => 'badge-entree',
                                    'recue_livree' => 'badge-ok',
                                    'annulee'      => 'badge-sortie',
                                ];
                            @endphp
                            <span class="{{ $couleurs[$commande->statut] }}">{{ $commande->statut_label }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('commandes.pdf', $commande) }}" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Supprimer cette commande ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Aucune commande pour l'instant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $commandes->links() }}
</div>

@endsection
