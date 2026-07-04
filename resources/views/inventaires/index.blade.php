@extends('layouts.app')
@section('title', 'Inventaires')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Inventaires</h2>
    <form method="POST" action="{{ route('inventaires.create') }}">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('Lancer un nouvel inventaire ? Le stock actuel de tous les produits sera capturé comme référence.');">
            <i class="bi bi-clipboard-check"></i> Lancer un inventaire
        </button>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Réalisé par</th>
                    <th>Statut</th>
                    <th>Écarts détectés</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventaires as $inv)
                    <tr>
                        <td class="fw-semibold">{{ $inv->numero }}</td>
                        <td>{{ $inv->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $inv->user->name ?? '—' }}</td>
                        <td>
                            @if($inv->statut === 'termine')
                                <span class="badge-ok">Terminé</span>
                            @else
                                <span class="badge-alerte">En cours</span>
                            @endif
                        </td>
                        <td>
                            @if($inv->statut === 'termine')
                                @if($inv->nombre_ecarts > 0)
                                    <span class="text-danger">{{ $inv->nombre_ecarts }} écart(s)</span>
                                @else
                                    <span class="text-success">Aucun écart</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($inv->statut === 'en_cours')
                                <a href="{{ route('inventaires.compter', $inv) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Continuer le comptage
                                </a>
                            @else
                                <a href="{{ route('inventaires.show', $inv) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Voir le détail
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun inventaire réalisé pour l'instant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $inventaires->links() }}
</div>

@endsection