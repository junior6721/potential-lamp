@extends('layouts.app')
@section('title', 'Nouvel utilisateur')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-person-plus me-2"></i>Créer un utilisateur
      </div>
      <div class="card-body p-4">
        <form method="POST" action="{{ route('users.store') }}">
          @csrf

          @if($errors->any())
            <div class="alert alert-danger mb-4">
              <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
              </ul>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Nom complet *</label>
            <input type="text" name="name" class="form-control"
              value="{{ old('name') }}" required autofocus
              placeholder="Jean Dupont"/>
          </div>

          <div class="mb-3">
            <label class="form-label">Adresse email *</label>
            <input type="email" name="email" class="form-control"
              value="{{ old('email') }}" required
              placeholder="jean@exemple.com"/>
          </div>

          <div class="mb-3">
            <label class="form-label">Rôle *</label>
            <select name="role" class="form-select" required>
              <option value="employe" {{ old('role') === 'employe' ? 'selected' : '' }}>
                👤 Employé
              </option>
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                🛡️ Administrateur
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Mot de passe *</label>
            <input type="password" name="password" class="form-control"
              required placeholder="Minimum 6 caractères"/>
          </div>

          <div class="mb-4">
            <label class="form-label">Confirmer le mot de passe *</label>
            <input type="password" name="password_confirmation" class="form-control"
              required placeholder="Répéter le mot de passe"/>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check me-1"></i>Créer l'utilisateur
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection