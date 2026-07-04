@extends('layouts.app')
@section('title', 'Clients')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="mb-0">Clients</h2>
<a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus"></i> Ajouter un client
</a>
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
                @forelse($clients as $client)
<tr>
<td class="fw-semibold">{{ $client->societe }}</td>
<td>{{ $client->contact ?? '—' }}</td>
<td>{{ $client->telephone ?? '—' }}</td>
<td>{{ $client->email ?? '—' }}</td>
<td>
                            @if($client->actif)
<span class="badge-ok">Actif</span>
                            @else
<span class="badge-alerte">Inactif</span>
                            @endif
</td>
<td class="text-end">
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline"
          onsubmit="return confirm('Supprimer ce client ?');">
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
<td colspan="6" class="text-center text-muted py-4">
                            Aucun client enregistré pour l'instant.
</td>
</tr>
                @endforelse
</tbody>
</table>
</div>
</div>
<div class="mt-3">
    {{ $clients->links() }}
</div>
@endsection