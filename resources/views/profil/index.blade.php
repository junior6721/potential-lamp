@extends('layouts.app')
@section('title', 'Mon Profil')

@section('content')
<div class="row g-3">

  <!-- ── COLONNE GAUCHE ── -->
  <div class="col-lg-4">

    <!-- Carte identité -->
    <div class="card mb-3">
      <div class="card-body p-4 text-center">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:30px;font-weight:700;color:#fff;margin:0 auto 1rem;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
        <div style="color:var(--muted);font-size:13px;margin-bottom:12px;">{{ auth()->user()->email }}</div>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          @if(auth()->user()->role === 'admin')
            <span style="background:rgba(26,115,232,0.15);color:#4d9fff;padding:5px 14px;border-radius:100px;font-size:12px;">
              <i class="bi bi-shield-check me-1"></i>Administrateur
            </span>
          @else
            <span style="background:rgba(108,92,231,0.15);color:#a78bfa;padding:5px 14px;border-radius:100px;font-size:12px;">
              <i class="bi bi-person me-1"></i>Employé
            </span>
          @endif
          <span style="background:rgba(40,167,69,0.15);color:#28a745;padding:5px 14px;border-radius:100px;font-size:12px;">
            <i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Actif
          </span>
        </div>
        <div style="color:var(--muted);font-size:11px;margin-top:12px;">
          <i class="bi bi-calendar me-1"></i>Membre depuis {{ auth()->user()->created_at->format('d/m/Y') }}
        </div>
      </div>
    </div>

    <!-- Stats activité -->
    <div class="card">
      <div class="card-header py-3 px-4">
        <span style="font-size:14px;font-weight:600;"><i class="bi bi-bar-chart me-2"></i>Mon activité</span>
      </div>
      <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center p-3" style="border-bottom:1px solid var(--border);">
          <span style="font-size:13px;color:var(--muted);">Total mouvements</span>
          <strong style="color:var(--accent2);">{{ $total_mouvements }}</strong>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3" style="border-bottom:1px solid var(--border);">
          <span style="font-size:13px;color:var(--muted);"><i class="bi bi-arrow-down-circle text-success me-1"></i>Entrées</span>
          <strong style="color:#28a745;">{{ $total_entrees }}</strong>
        </div>
        <div class="d-flex justify-content-between align-items-center p-3">
          <span style="font-size:13px;color:var(--muted);"><i class="bi bi-arrow-up-circle text-danger me-1"></i>Sorties</span>
          <strong style="color:#dc3545;">{{ $total_sorties }}</strong>
        </div>
      </div>
    </div>

  </div>

  <!-- ── COLONNE DROITE ── -->
  <div class="col-lg-8">

    <!-- Onglets Bootstrap natifs (pas de JS custom) -->
    <ul class="nav nav-tabs mb-0" id="profilTabs" role="tablist"
        style="border-bottom:1px solid var(--border);">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-info-btn"
          data-bs-toggle="tab" data-bs-target="#panel-info"
          type="button" role="tab"
          style="color:var(--muted);background:transparent;border:none;padding:10px 18px;font-size:14px;">
          <i class="bi bi-person me-1"></i>Mes informations
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-pwd-btn"
          data-bs-toggle="tab" data-bs-target="#panel-password"
          type="button" role="tab"
          style="color:var(--muted);background:transparent;border:none;padding:10px 18px;font-size:14px;">
          <i class="bi bi-lock me-1"></i>Mot de passe
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-history-btn"
          data-bs-toggle="tab" data-bs-target="#panel-history"
          type="button" role="tab"
          style="color:var(--muted);background:transparent;border:none;padding:10px 18px;font-size:14px;">
          <i class="bi bi-clock-history me-1"></i>Historique
        </button>
      </li>
    </ul>

    <div class="tab-content">

      <!-- ── ONGLET INFOS ── -->
      <div class="tab-pane fade show active" id="panel-info" role="tabpanel">
        <div class="card" style="border-top-left-radius:0;border-top-right-radius:0;">
          <div class="card-header py-3 px-4">
            <i class="bi bi-person-gear me-2"></i>Modifier mes informations
          </div>
          <div class="card-body p-4">

            @if(session('success_info'))
              <div class="alert alert-success mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success_info') }}
              </div>
            @endif

            @if($errors->has('name') || $errors->has('email'))
              <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                  @foreach($errors->only(['name','email']) as $e)<li>{{ $e }}</li>@endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('profil.updateInfo') }}">
              @csrf @method('PUT')
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Nom complet *</label>
                  <input type="text" name="name" class="form-control"
                    value="{{ old('name', auth()->user()->name) }}" required/>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Adresse email *</label>
                  <input type="email" name="email" class="form-control"
                    value="{{ old('email', auth()->user()->email) }}" required/>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Rôle</label>
                  <input type="text" class="form-control"
                    value="{{ ucfirst(auth()->user()->role) }}" disabled
                    style="opacity:0.5;cursor:not-allowed;"/>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Membre depuis</label>
                  <input type="text" class="form-control"
                    value="{{ auth()->user()->created_at->format('d/m/Y') }}" disabled
                    style="opacity:0.5;cursor:not-allowed;"/>
                </div>
              </div>
              <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check me-1"></i>Enregistrer
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- ── ONGLET MOT DE PASSE ── -->
      <div class="tab-pane fade" id="panel-password" role="tabpanel">
        <div class="card" style="border-top-left-radius:0;border-top-right-radius:0;">
          <div class="card-header py-3 px-4">
            <i class="bi bi-lock me-2"></i>Changer mon mot de passe
          </div>
          <div class="card-body p-4">

            @if(session('success_pwd'))
              <div class="alert alert-success mb-4">
                <i class="bi bi-check-circle me-2"></i>{{ session('success_pwd') }}
              </div>
            @endif

            @if($errors->has('current_password') || $errors->has('password'))
              <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                  @foreach($errors->only(['current_password','password']) as $e)<li>{{ $e }}</li>@endforeach
                </ul>
              </div>
            @endif

            <form method="POST" action="{{ route('profil.updatePassword') }}">
              @csrf @method('PUT')
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label">Mot de passe actuel *</label>
                  <div style="position:relative;">
                    <input type="password" name="current_password" id="pwd-current"
                      class="form-control" placeholder="Votre mot de passe actuel" required/>
                    <button type="button" onclick="togglePwd('pwd-current')"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nouveau mot de passe *</label>
                  <div style="position:relative;">
                    <input type="password" name="password" id="pwd-new"
                      class="form-control" placeholder="Minimum 6 caractères" required/>
                    <button type="button" onclick="togglePwd('pwd-new')"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Confirmer *</label>
                  <div style="position:relative;">
                    <input type="password" name="password_confirmation" id="pwd-confirm"
                      class="form-control" placeholder="Répéter le mot de passe" required/>
                    <button type="button" onclick="togglePwd('pwd-confirm')"
                      style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;">
                      <i class="bi bi-eye"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Force mot de passe -->
              <div class="mt-3" id="pwd-strength-wrap" style="display:none;">
                <div style="font-size:12px;color:var(--muted);margin-bottom:6px;">Force :</div>
                <div style="height:4px;background:var(--border);border-radius:4px;overflow:hidden;">
                  <div id="pwd-strength-bar" style="height:100%;width:0%;border-radius:4px;transition:all 0.3s;"></div>
                </div>
                <div id="pwd-strength-label" style="font-size:11px;margin-top:4px;"></div>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-lock me-1"></i>Changer le mot de passe
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- ── ONGLET HISTORIQUE ── -->
      <div class="tab-pane fade" id="panel-history" role="tabpanel">
        <div class="card" style="border-top-left-radius:0;border-top-right-radius:0;">
          <div class="card-header py-3 px-4">
            <i class="bi bi-clock-history me-2"></i>Mes 5 dernières opérations
          </div>
          <div class="table-responsive">
            <table class="table mb-0">
              <thead>
                <tr>
                  <th>Date</th><th>Produit</th><th>Type</th>
                  <th>Quantité</th><th>Stock après</th><th>Bon</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mouvements as $m)
                <tr>
                  <td style="font-size:12px;color:var(--muted);">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                  <td><strong>{{ $m->produit->nom ?? '-' }}</strong></td>
                  <td>
                    @if($m->type === 'entree')
                      <span class="badge-entree"><i class="bi bi-arrow-down-circle me-1"></i>Entrée</span>
                    @else
                      <span class="badge-sortie"><i class="bi bi-arrow-up-circle me-1"></i>Sortie</span>
                    @endif
                  </td>
                  <td><strong>{{ $m->quantite }}</strong></td>
                  <td>{{ $m->stock_apres }}</td>
                  <td>
                    <a href="{{ route('pdf.bon', $m) }}" target="_blank"
                       class="btn btn-sm btn-outline-danger" title="Bon PDF">
                      <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center py-4" style="color:var(--muted);">
                    Aucun mouvement effectué
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($total_mouvements > 5)
          <div class="card-footer text-center py-3">
            <a href="{{ route('mouvements.index') }}" style="font-size:13px;color:var(--accent2);text-decoration:none;">
              Voir tous mes mouvements ({{ $total_mouvements }}) →
            </a>
          </div>
          @endif
        </div>
      </div>

    </div><!-- end tab-content -->
  </div>
</div>
@endsection

@section('scripts')
<style>
  .nav-tabs .nav-link { border: none !important; border-radius: 0 !important; }
  .nav-tabs .nav-link.active {
    color: var(--text) !important;
    background: var(--card) !important;
    border-bottom: 2px solid var(--accent) !important;
  }
  .nav-tabs .nav-link:hover { color: var(--text) !important; }

  /* Ouvrir l'onglet mot de passe si erreur dessus */
  @if(session('tab') === 'password' || $errors->has('current_password') || $errors->has('password'))
  document.addEventListener('DOMContentLoaded', function() {
    var tab = new bootstrap.Tab(document.getElementById('tab-pwd-btn'));
    tab.show();
  });
  @endif
</style>
<script>
  // Afficher/masquer mot de passe
  function togglePwd(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
  }

  // Force du mot de passe
  const pwdNew = document.getElementById('pwd-new');
  if (pwdNew) {
    pwdNew.addEventListener('input', function() {
      const val  = this.value;
      const wrap = document.getElementById('pwd-strength-wrap');
      const bar  = document.getElementById('pwd-strength-bar');
      const lbl  = document.getElementById('pwd-strength-label');
      if (!val) { wrap.style.display = 'none'; return; }
      wrap.style.display = 'block';
      let score = 0;
      if (val.length >= 6)  score++;
      if (val.length >= 10) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[0-9]/.test(val)) score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;
      const levels = [
        { w:'20%', bg:'#dc3545', txt:'Très faible' },
        { w:'40%', bg:'#fd7e14', txt:'Faible' },
        { w:'60%', bg:'#ffc107', txt:'Moyen' },
        { w:'80%', bg:'#20c997', txt:'Fort' },
        { w:'100%',bg:'#28a745', txt:'Très fort' },
      ];
      const lvl = levels[Math.min(score, 4)];
      bar.style.width      = lvl.w;
      bar.style.background = lvl.bg;
      lbl.textContent      = lvl.txt;
      lbl.style.color      = lvl.bg;
    });
  }

  // Ouvrir onglet mot de passe si erreur
  @if(session('tab') === 'password' || $errors->has('current_password') || $errors->has('password'))
  document.addEventListener('DOMContentLoaded', function() {
    var tabEl = document.getElementById('tab-pwd-btn');
    var tab = new bootstrap.Tab(tabEl);
    tab.show();
  });
  @endif
</script>
@endsection