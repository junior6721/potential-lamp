@extends('layouts.app')
@section('title', 'Historique des connexions')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Historique des connexions</h2>
    @if($connexions->total() > 0)
    <form action="{{ route('historique-connexions.destroy') }}" method="POST"
          onsubmit="return confirm('Vider tout l\'historique des connexions ? Cette action est irréversible.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-trash me-1"></i> Vider l'historique
        </button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Date et heure</th>
                    <th>Adresse IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($connexions as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->user->name ?? 'Utilisateur supprimé' }}</td>
                        <td>
                            @if($c->user)
                                @if($c->user->role === 'admin')
                                    <span class="badge-ok">Admin</span>
                                @else
                                    <span class="badge-alerte">Employé</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $c->connecte_a->format('d/m/Y à H:i:s') }}</td>
                        <td class="text-muted">{{ $c->adresse_ip ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Aucune connexion enregistrée pour l'instant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $connexions->links() }}
</div>

@endsection