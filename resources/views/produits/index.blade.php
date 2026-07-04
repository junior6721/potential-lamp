@extends('layouts.app')
@section('title', 'Produits')

@section('content')
<!-- Filtres -->
<div class="card mb-4">
  <div class="card-body py-3">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="🔍 Rechercher nom ou référence..." value="{{ request('search') }}"/>
      </div>
      <div class="col-md-3">
        <select name="categorie_id" class="form-select">
          <option value="">Toutes les catégories</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ request('categorie_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" name="alerte" id="alerte" {{ request('alerte') ? 'checked' : '' }}>
          <label class="form-check-label" for="alerte" style="font-size:13px;">⚠️ Alertes seules</label>
        </div>
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i> Filtrer</button>
        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
        <a href="{{ route('pdf.stock') }}" class="btn btn-danger" target="_blank" title="Exporter PDF"><i class="bi bi-file-earmark-pdf"></i> PDF</a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('produits.create') }}" class="btn btn-success"><i class="bi bi-plus"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
    <span><i class="bi bi-box me-2"></i>{{ $produits->total() }} produit(s)</span>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead><tr>
        <th>Référence</th><th>Nom</th><th>Catégorie</th>
        <th>Prix achat</th><th>Prix vente</th>
        <th>Stock</th><th>Statut</th><th>Actions</th>
      </tr></thead>
      <tbody>
        @forelse($produits as $p)
        <tr>
          <td><code style="color:#4d9fff;">{{ $p->reference }}</code></td>
          <td><strong>{{ $p->nom }}</strong></td>
          <td><span style="background:rgba(108,92,231,0.15);color:#a78bfa;padding:3px 10px;border-radius:100px;font-size:12px;">{{ $p->categorie->nom ?? '-' }}</span></td>
          <td>{{ number_format($p->prix_achat, 0, ',', ' ') }} F</td>
          <td>{{ number_format($p->prix_vente, 0, ',', ' ') }} F</td>
          <td>
            @if($p->enRuptureDeStock())
              <span class="badge-alerte"><i class="bi bi-exclamation-triangle me-1"></i>{{ $p->quantite_stock }} {{ $p->unite }}</span>
            @else
              <span class="badge-ok"><i class="bi bi-check me-1"></i>{{ $p->quantite_stock }} {{ $p->unite }}</span>
            @endif
          </td>
          <td>
            @if($p->actif)
              <span style="color:#28a745;font-size:12px;"><i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>Actif</span>
            @else
              <span style="color:#9e9e9e;font-size:12px;"><i class="bi bi-circle me-1" style="font-size:8px;"></i>Inactif</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('produits.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Détails"><i class="bi bi-eye"></i></a>
              @if(auth()->user()->isAdmin())
              <a href="{{ route('produits.edit', $p) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('produits.destroy', $p) }}" onsubmit="return confirm('Désactiver ce produit ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Désactiver"><i class="bi bi-slash-circle"></i></button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-5" style="color:#9e9e9e;">Aucun produit trouvé</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($produits->hasPages())
  <div class="card-footer py-3 d-flex justify-content-center">
    {{ $produits->withQueryString()->links() }}
  </div>
  @endif
</div>
@endsection
