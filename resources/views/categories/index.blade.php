@extends('layouts.app')
@section('title', 'Catégories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div></div>
  @if(auth()->user()->isAdmin())
  <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus me-1"></i>Nouvelle catégorie</a>
  @endif
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr><th>#</th><th>Nom</th><th>Description</th><th>Nb produits</th><th>Créée le</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($categories as $c)
        <tr>
          <td style="color:#9e9e9e;">{{ $c->id }}</td>
          <td><strong>{{ $c->nom }}</strong></td>
          <td style="color:#9e9e9e;font-size:13px;">{{ $c->description ?? '-' }}</td>
          <td>
            <span style="background:rgba(26,115,232,0.15);color:#4d9fff;padding:3px 10px;border-radius:100px;font-size:12px;">
              {{ $c->produits_count }} produit(s)
            </span>
          </td>
          <td style="font-size:12px;color:#9e9e9e;">{{ $c->created_at->format('d/m/Y') }}</td>
          <td>
            @if(auth()->user()->isAdmin())
            <div class="d-flex gap-1">
              <a href="{{ route('categories.edit', $c) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </div>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center py-5" style="color:#9e9e9e;">Aucune catégorie</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($categories->hasPages())
  <div class="card-footer py-3 d-flex justify-content-center">{{ $categories->links() }}</div>
  @endif
</div>
@endsection
