@extends('layouts.app')
@section('title', 'Fournisseurs')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Fournisseurs</h2>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('fournisseurs.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Ajouter un fournisseur
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Société</th>
                    <th>Contact</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fournisseurs as $fournisseur)
                    <tr>
                        <td class="fw-semibold">{{ $fournisseur->societe }}</td>
                        <td>{{ $fournisseur->contact ?? '—' }}</td>
                        <td>{{ $fournisseur->telephone ?? '—' }}</td>
                        <td>{{ $fournisseur->email ?? '—' }}</td>
                        <td>
                            @if($fournisseur->actif)
                                <span class="badge-ok">Actif</span>
                            @else
                                <span class="badge-alerte">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Supprimer ce fournisseur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Lecture seule</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun fournisseur enregistré pour l'instant.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $fournisseurs->links() }}
</div>

@endsection