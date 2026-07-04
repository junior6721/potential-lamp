@extends('layouts.app')
@section('title', 'Ajouter un client')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Ajouter un client</h2>
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body p-4">

        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('clients.store') }}">
            @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                    <label class="form-label">Société / Entreprise (si applicable)</label>
                    <input type="text" name="societe" class="form-control" value="{{ old('societe') }}">
                </div>
        
                <div class="col-md-6">
                    <label class="form-label">Nom du contact / Particulier</label>
                    <input type="text" name="contact" class="form-control" value="{{ old('contact') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="2">{{ old('adresse') }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" checked>
                        <label class="form-check-label" for="actif">Client actif</label>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Enregistrer
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>

    </div>
</div>

@endsection
