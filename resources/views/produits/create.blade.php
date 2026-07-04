@extends('layouts.app')
@section('title', isset($produit) ? 'Modifier le produit' : 'Nouveau produit')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-{{ isset($produit) ? 'pencil' : 'plus-circle' }} me-2"></i>
        {{ isset($produit) ? 'Modifier : '.$produit->nom : 'Ajouter un nouveau produit' }}
      </div>
      <div class="card-body p-4">
        <form method="POST" action="{{ isset($produit) ? route('produits.update', $produit) : route('produits.store') }}">
          @csrf
          @if(isset($produit)) @method('PUT') @endif

          @if($errors->any())
            <div class="alert alert-danger mb-4">
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
          @endif

          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label">Nom du produit *</label>
              <input type="text" name="nom" class="form-control" value="{{ old('nom', $produit->nom ?? '') }}" required/>
            </div>
            <div class="col-md-4">
              <label class="form-label">Référence *</label>
              <input type="text" name="reference" class="form-control" value="{{ old('reference', $produit->reference ?? '') }}" required/>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="2">{{ old('description', $produit->description ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Catégorie *</label>
              <select name="categorie_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Unité *</label>
              <select name="unite" class="form-select">
                @foreach(['unité','kg','g','litre','ml','m','cm','boîte','sac','carton','pièce'] as $u)
                  <option value="{{ $u }}" {{ old('unite', $produit->unite ?? 'unité') == $u ? 'selected' : '' }}>{{ $u }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Stock minimum *</label>
              <input type="number" name="stock_minimum" class="form-control" value="{{ old('stock_minimum', $produit->stock_minimum ?? 5) }}" min="0" required/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prix d'achat (FCFA) *</label>
              <input type="number" name="prix_achat" class="form-control" value="{{ old('prix_achat', $produit->prix_achat ?? '') }}" step="0.01" min="0" required/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Prix de vente (FCFA) *</label>
              <input type="number" name="prix_vente" class="form-control" value="{{ old('prix_vente', $produit->prix_vente ?? '') }}" step="0.01" min="0" required/>
            </div>
            @if(!isset($produit))
            <div class="col-md-6">
              <label class="form-label">Stock initial</label>
              <input type="number" name="quantite_stock" class="form-control" value="{{ old('quantite_stock', 0) }}" min="0"/>
            </div>
            @endif
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check me-1"></i>{{ isset($produit) ? 'Mettre à jour' : 'Ajouter le produit' }}
            </button>
            <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
