@extends('layouts.app')
@section('title', 'Modifier utilisateur')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-pencil me-2"></i>Modifier : {{ $user->name }}
      </div>
      <div class="card-body p-4">
        <form method="POST" action="{{ route('users.update', $user) }}">
          @csrf
          @method('PUT')

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
              value="{{ old('name', $user->name) }}" required/>
          </div>

          <div class="mb-3">
            <label class="form-label">Adresse email *</label>
            <input type="email" name="email" class="form-control"
              value="{{ old('email', $user->email) }}" required/>
          </div>

          <div class="mb-3">
            <label class="form-label">Rôle *</label>
            <select name="role" class="form-select" required>
              <option value="employe" {{ old('role', $user->role) === 'employe' ? 'selected' : '' }}>
                👤 Employé
              </option>
              <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                🛡️ Administrateur
              </option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Statut</label>
            <select name="actif" class="form-select">
              <option value="1" {{ old('actif', $user->actif) ? 'selected' : '' }}>✅ Actif</option>
              <option value="0" {{ !old('actif', $user->actif) ? 'selected' : '' }}>❌ Inactif</option>
            </select>
          </div>

          <hr style="border-color:rgba(255,255,255,0.08);">
          <p style="font-size:13px;color:#9e9e9e;">Laisser vide pour ne pas changer le mot de passe</p>

          <div class="mb-3">
            <label class="form-label">Nouveau mot de passe</label>
            <input type="password" name="password" class="form-control"
              placeholder="Minimum 6 caractères"/>
          </div>

          <div class="mb-4">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="form-control"
              placeholder="Répéter le mot de passe"/>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check me-1"></i>Mettre à jour
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection