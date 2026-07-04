@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div style="color:#9e9e9e;font-size:14px;">{{ $users->total() }} utilisateur(s)</div>
  <a href="{{ route('users.create') }}" class="btn btn-primary">
    <i class="bi bi-person-plus me-1"></i>Nouvel utilisateur
  </a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td style="color:#9e9e9e;">{{ $user->id }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div style="width:32px;height:32px;background:{{ $user->role === 'admin' ? '#1a73e8' : '#6c5ce7' }};border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
              <strong>{{ $user->name }}</strong>
              @if($user->id === auth()->id())
                <span style="font-size:11px;background:rgba(26,115,232,0.15);color:#4d9fff;padding:2px 8px;border-radius:100px;">Vous</span>
              @endif
            </div>
          </td>
          <td style="color:#9e9e9e;">{{ $user->email }}</td>
          <td>
            @if($user->role === 'admin')
              <span style="background:rgba(26,115,232,0.15);color:#4d9fff;padding:4px 12px;border-radius:100px;font-size:12px;">
                <i class="bi bi-shield-check me-1"></i>Admin
              </span>
            @else
              <span style="background:rgba(108,92,231,0.15);color:#a78bfa;padding:4px 12px;border-radius:100px;font-size:12px;">
                <i class="bi bi-person me-1"></i>Employé
              </span>
            @endif
          </td>
          <td>
            @if($user->actif)
              <span style="color:#28a745;font-size:12px;"><i class="bi bi-circle-fill me-1" style="font-size:8px;"></i>Actif</span>
            @else
              <span style="color:#9e9e9e;font-size:12px;"><i class="bi bi-circle me-1" style="font-size:8px;"></i>Inactif</span>
            @endif
          </td>
          <td style="font-size:12px;color:#9e9e9e;">{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                <i class="bi bi-pencil"></i>
              </a>
              @if($user->id !== auth()->id())
              <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-5" style="color:#9e9e9e;">
            <i class="bi bi-people" style="font-size:32px;display:block;margin-bottom:8px;"></i>
            Aucun utilisateur trouvé
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div class="card-footer py-3 d-flex justify-content-center">
    {{ $users->links() }}
  </div>
  @endif
</div>
@endsection