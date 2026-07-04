@extends('layouts.app')
@section('title', isset($categorie) ? 'Modifier catégorie' : 'Nouvelle catégorie')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-tags me-2"></i>{{ isset($categorie) ? 'Modifier : '.$categorie->nom : 'Nouvelle catégorie' }}
      </div>
      <div class="card-body p-4">
        <form method="POST" action="{{ isset($categorie) ? route('categories.update', $categorie) : route('categories.store') }}">
          @csrf
          @if(isset($categorie)) @method('PUT') @endif

          @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          <div class="mb-3">
            <label class="form-label">Nom de la catégorie *</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $categorie->nom ?? '') }}" required autofocus/>
          </div>
          <div class="mb-4">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $categorie->description ?? '') }}</textarea>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check me-1"></i>Enregistrer</button>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
