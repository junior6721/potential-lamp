@extends('layouts.app')
@section('title', $produit->nom)

@section('content')
<div class="row g-3">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body p-4">
        <h5 class="mb-1">{{ $produit->nom }}</h5>
        <code style="color:#4d9fff;">{{ $produit->reference }}</code>
        <hr style="border-color:rgba(255,255,255,0.08);">
        <div class="d-flex flex-column gap-3">
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Catégorie</span>
            <span>{{ $produit->categorie->nom ?? '-' }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Prix achat</span>
            <span>{{ number_format($produit->prix_achat,0,',',' ') }} F</span>
          </div>
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Prix vente</span>
            <span>{{ number_format($produit->prix_vente,0,',',' ') }} F</span>
          </div>
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Stock actuel</span>
            <strong class="{{ $produit->enRuptureDeStock() ? 'text-warning' : 'text-success' }}">
              {{ $produit->quantite_stock }} {{ $produit->unite }}
            </strong>
          </div>
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Stock minimum</span>
            <span>{{ $produit->stock_minimum }} {{ $produit->unite }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span style="color:#9e9e9e;">Valeur stock</span>
            <strong>{{ number_format($produit->quantite_stock * $produit->prix_achat,0,',',' ') }} F</strong>
          </div>
        </div>
        <div class="d-flex gap-2 mt-4">
          <a href="{{ route('mouvements.create') }}?produit_id={{ $produit->id }}" class="btn btn-primary btn-sm flex-fill">
            <i class="bi bi-plus"></i> Mouvement
          </a>
          @if(auth()->user()->isAdmin())
          <a href="{{ route('produits.edit', $produit) }}" class="btn btn-outline-warning btn-sm flex-fill">
            <i class="bi bi-pencil"></i> Modifier
          </a>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-clock-history me-2"></i>Historique des mouvements
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead><tr><th>Type</th><th>Qté</th><th>Avant</th><th>Après</th><th>Motif</th><th>Par</th><th>Date</th></tr></thead>
          <tbody>
            @forelse($mouvements as $m)
            <tr>
              <td>
                @if($m->type === 'entree')
                  <span class="badge-entree"><i class="bi bi-arrow-down-circle me-1"></i>Entrée</span>
                @else
                  <span class="badge-sortie"><i class="bi bi-arrow-up-circle me-1"></i>Sortie</span>
                @endif
              </td>
              <td><strong>{{ $m->quantite }}</strong></td>
              <td style="color:#9e9e9e;">{{ $m->stock_avant }}</td>
              <td><strong>{{ $m->stock_apres }}</strong></td>
              <td style="font-size:12px;">{{ $m->motif ?? '-' }}</td>
              <td style="font-size:12px;">{{ $m->user->name ?? '-' }}</td>
              <td style="font-size:12px;color:#9e9e9e;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4" style="color:#9e9e9e;">Aucun mouvement</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if($mouvements->hasPages())
      <div class="card-footer py-3 d-flex justify-content-center">{{ $mouvements->links() }}</div>
      @endif
    </div>
  </div>
</div>
@endsection
