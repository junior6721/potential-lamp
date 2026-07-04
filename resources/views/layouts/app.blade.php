<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Inventix')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    /* ── THÈME SOMBRE ── */
    :root, [data-theme="dark"] {
      --bg:       #0f0f0f;
      --bg2:      #1a1a1a;
      --card:     #242424;
      --sidebar:  #141414;
      --accent:   #1a73e8;
      --accent2:  #4d9fff;
      --text:     #ffffff;
      --muted:    #9e9e9e;
      --border:   rgba(255,255,255,0.08);
      --nav-bg:   rgba(20,20,20,0.96);
      --input-bg: #1e1e1e;
      --shadow:   0 4px 24px rgba(0,0,0,0.5);
      --badge-text: #fff;
      --table-hover: rgba(255,255,255,0.03);
    }

    /* ── THÈME CLAIR ── */
    [data-theme="light"] {
      --bg:       #f0f2f5;
      --bg2:      #ffffff;
      --card:     #ffffff;
      --sidebar:  #1e2a3a;
      --accent:   #1a73e8;
      --accent2:  #4d9fff;
      --text:     #1a1a2e;
      --muted:    #6b7280;
      --border:   rgba(0,0,0,0.08);
      --nav-bg:   rgba(240,242,245,0.96);
      --input-bg: #f9fafb;
      --shadow:   0 4px 24px rgba(0,0,0,0.08);
      --badge-text: #fff;
      --table-hover: rgba(0,0,0,0.02);
    }

    /* ── BASE ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: 'Segoe UI', sans-serif;
      transition: background 0.3s, color 0.3s;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; top: 0; left: 0; width: 250px;
      height: 100vh; background: var(--sidebar);
      border-right: 1px solid var(--border);
      display: flex; flex-direction: column; z-index: 100;
      overflow-y: auto; transition: background 0.3s, border-color 0.3s;
    }
    .sidebar-brand {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--border);
      font-size: 16px; font-weight: 700; color: var(--accent2);
      display: flex; align-items: center; gap: 10px;
    }
    .sidebar-brand i { font-size: 22px; }
    .sidebar-section {
      font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase;
      color: var(--muted); padding: 1rem 1.5rem 0.4rem;
    }
    .sidebar-link {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 1.5rem; color: var(--muted);
      text-decoration: none; font-size: 14px;
      transition: all 0.2s; border-left: 3px solid transparent;
    }
    .sidebar-link:hover, .sidebar-link.active {
      color: var(--text); background: rgba(26,115,232,0.1);
      border-left-color: var(--accent);
    }
    [data-theme="light"] .sidebar-link { color: rgba(255,255,255,0.65); }
    [data-theme="light"] .sidebar-link:hover,
    [data-theme="light"] .sidebar-link.active { color: #fff; background: rgba(255,255,255,0.15); }
    [data-theme="light"] .sidebar-section { color: rgba(255,255,255,0.4); }
    .sidebar-link i { font-size: 16px; width: 20px; }

    /* ── THEME TOGGLE dans sidebar ── */
    .theme-switch-wrap {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 1.5rem; margin: 0;
      border-top: 1px solid var(--border);
    }
    .theme-switch-label {
      font-size: 13px; color: var(--muted); display: flex; align-items: center; gap: 8px;
    }
    [data-theme="light"] .theme-switch-label { color: rgba(255,255,255,0.6); }
    .toggle-btn {
      width: 44px; height: 24px; border-radius: 100px;
      background: var(--border); border: none; cursor: pointer;
      position: relative; transition: background 0.3s; flex-shrink: 0;
      outline: none;
    }
    [data-theme="light"] .toggle-btn { background: rgba(255,255,255,0.3); }
    .toggle-btn::after {
      content: ''; position: absolute; top: 3px; left: 3px;
      width: 18px; height: 18px; border-radius: 50%;
      background: var(--accent2); transition: transform 0.3s;
    }
    [data-theme="light"] .toggle-btn::after { transform: translateX(20px); background: #fff; }

    /* ── SIDEBAR FOOTER ── */
    .sidebar-footer {
      padding: 1rem 1.5rem;
      border-top: 1px solid var(--border);
    }
    .badge-role {
      font-size: 10px; padding: 2px 8px; border-radius: 100px;
      background: rgba(26,115,232,0.2); color: var(--accent2);
    }
    [data-theme="light"] .badge-role { background: rgba(255,255,255,0.2); color: #fff; }

    /* ── MAIN ── */
    .main-content { margin-left: 250px; min-height: 100vh; }
    .topbar {
      background: var(--nav-bg); border-bottom: 1px solid var(--border);
      padding: 0.75rem 1.5rem;
      display: flex; justify-content: space-between; align-items: center;
      position: sticky; top: 0; z-index: 50;
      backdrop-filter: blur(10px);
      transition: background 0.3s, border-color 0.3s;
    }
    .topbar-title { font-size: 18px; font-weight: 600; }
    .page-body { padding: 1.5rem; }

    /* ── CARDS ── */
    .card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: 12px; color: var(--text);
      transition: background 0.3s, border-color 0.3s;
    }
    .card-header {
      background: transparent; border-bottom: 1px solid var(--border);
      color: var(--text);
    }
    .stat-card {
      border-radius: 12px; padding: 1.25rem;
      background: var(--card); border: 1px solid var(--border);
      transition: transform 0.2s, background 0.3s, border-color 0.3s;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-icon {
      width: 48px; height: 48px; border-radius: 12px;
      display: flex; align-items: center; justify-content: center; font-size: 22px;
    }
    .stat-num { font-size: 28px; font-weight: 700; }
    .stat-label { font-size: 13px; color: var(--muted); }

    /* ── TABLES ── */
    .table { color: var(--text); }
    .table thead th {
      background: rgba(128,128,128,0.08);
      border-color: var(--border); font-size: 12px;
      text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted);
    }
    .table td, .table th { border-color: var(--border); vertical-align: middle; }
    .table tbody tr:hover { background: var(--table-hover); }

    /* ── FORMS ── */
    .form-control, .form-select {
      background: var(--input-bg); border: 1px solid var(--border);
      color: var(--text); border-radius: 8px;
      transition: background 0.3s, border-color 0.3s, color 0.3s;
    }
    .form-control:focus, .form-select:focus {
      background: var(--input-bg); color: var(--text);
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(26,115,232,0.15);
    }
    .form-control::placeholder { color: var(--muted); }
    .form-label { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    [data-theme="light"] .form-select option { background: #fff; color: #1a1a2e; }

    /* ── BUTTONS ── */
    .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
    .btn-primary:hover { background: #1557b0; border-color: #1557b0; color: #fff; }
    .btn-outline-primary { color: var(--accent); border-color: var(--accent); }
    .btn-outline-primary:hover { background: var(--accent); color: #fff; }
    .btn-outline-secondary {
      color: var(--muted); border-color: var(--border);
    }
    .btn-outline-secondary:hover { background: var(--border); color: var(--text); }

    /* ── BADGES ── */
    .badge-entree { background: rgba(40,167,69,0.15); color: #28a745; padding: 4px 10px; border-radius: 100px; font-size: 12px; }
    .badge-sortie { background: rgba(220,53,69,0.15); color: #dc3545; padding: 4px 10px; border-radius: 100px; font-size: 12px; }
    .badge-alerte { background: rgba(255,193,7,0.15); color: #d97706; padding: 4px 10px; border-radius: 100px; font-size: 12px; }
    .badge-ok     { background: rgba(40,167,69,0.15); color: #28a745; padding: 4px 10px; border-radius: 100px; font-size: 12px; }

    /* ── ALERTS ── */
    .alert { border-radius: 10px; font-size: 14px; }
    .alert-success { background: rgba(40,167,69,0.12); border-color: rgba(40,167,69,0.3); color: #28a745; }
    .alert-danger  { background: rgba(220,53,69,0.12); border-color: rgba(220,53,69,0.3); color: #dc3545; }

    /* ── PAGINATION ── */
    .pagination .page-link { background: var(--card); border-color: var(--border); color: var(--text); }
    .pagination .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; }
    .pagination .page-link:hover { background: var(--bg2); color: var(--text); }

    

    /* ── CLOCHE NOTIFICATIONS ── */
  .notif-bell-btn {
    font-size: 1.2rem;
    color: var(--muted);
    border: none;
    background: transparent;
    position: relative;
    padding: 6px 10px;
  }
  .notif-bell-btn:hover { color: var(--text); }
  .notif-badge {
    position: absolute;
    top: 0px;
    right: 0px;
    font-size: 0.65rem;
    padding: 3px 6px;
    line-height: 1;
  }
  .notif-dropdown {
    min-width: 320px;
    max-height: 400px;
    overflow-y: auto;
    padding: 0;
    background: var(--card);
    border: 1px solid var(--border);
  }
  .notif-header {
    padding: 10px 16px;
    font-weight: 600;
    font-size: 13px;
    color: var(--text);
    border-bottom: 1px solid var(--border);
  }
  .notif-item {
    padding: 10px 16px;
    white-space: normal;
    color: var(--text);
  }
  .notif-item:hover { background: var(--table-hover); }

    /* ── MOBILE ── */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); transition: transform 0.3s; }
      .sidebar.open { transform: translateX(0); }
      .main-content { margin-left: 0; }
    }
  </style>
</head>
<body>

  <!-- ══ SIDEBAR ══ -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <i class="bi bi-box-seam"></i> Inventix
    </div>

    <div class="sidebar-section">Principal</div>
    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid"></i> Tableau de bord
    </a>

    <div class="sidebar-section">Inventaire</div>
    <a href="{{ route('produits.index') }}" class="sidebar-link {{ request()->routeIs('produits.*') ? 'active' : '' }}">
      <i class="bi bi-box"></i> Produits
    </a>
    <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
      <i class="bi bi-tags"></i> Catégories
    </a>

    <a href="{{ route('fournisseurs.index') }}" class="sidebar-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}">
      <i class="bi bi-truck"></i> Fournisseurs
    </a>
    <a href="{{ route('clients.index') }}" class="sidebar-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
      <i class="bi bi-person-badge"></i> Clients
    </a>

    <div class="sidebar-section">Mouvements</div>
    <a href="{{ route('mouvements.index') }}" class="sidebar-link {{ request()->routeIs('mouvements.index') ? 'active' : '' }}">
      <i class="bi bi-arrow-left-right"></i> Entrées / Sorties
    </a>
    <a href="{{ route('mouvements.create') }}" class="sidebar-link {{ request()->routeIs('mouvements.create') ? 'active' : '' }}">
      <i class="bi bi-plus-circle"></i> Nouveau mouvement
    </a>

    <div class="sidebar-section">Commandes et Factures</div>
    <a href="{{ route('commandes.index') }}" class="sidebar-link {{ request()->routeIs('commandes.*') ? 'active' : '' }}">
      <i class="bi bi-file-earmark-text"></i> Bons de commande
    </a>
           
    <a href="{{ route('factures.index') }}" class="sidebar-link {{ request()->routeIs('factures.*') ? 'active' : '' }}">
      <i class="bi bi-cash-stack"></i> Factures
    </a>

      <!--   -->
    <div class="sidebar-section">Rapports</div>    
    <a href="{{ route('rapports.index') }}" class="sidebar-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}">
      <i class="bi bi-clipboard-data"></i> Rapport mensuel
    </a>
    <!--   -->  

    <div class="sidebar-section">Inventaires</div>        
    <a href="{{ route('inventaires.index') }}" class="sidebar-link {{ request()->routeIs('inventaires.*') ? 'active' : '' }}">
      <i class="bi bi-clipboard-check"></i> Inventaire
    </a>

    <div class="sidebar-section">Paramètres</div>            
    <a href="{{ route('parametres.index') }}" class="sidebar-link {{ request()->routeIs('parametres.*') ? 'active' : '' }}">
      <i class="bi bi-gear"></i> Paramètres
    </a>

    @if(auth()->user()->isAdmin())
      <a href="{{ route('historique-connexions.index') }}" class="sidebar-link {{ request()->routeIs('historique-connexions.*') ? 'active' : '' }}">
       <i class="bi bi-clock-history"></i> Historique connexions
      </a>
    @endif

    @if(auth()->user()->isAdmin())
    <div class="sidebar-section">Administration</div>
    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Utilisateurs
    </a>
    @endif

    <!-- Lien profil -->
    <a href="{{ route('profil') }}" class="sidebar-link {{ request()->routeIs('profil') ? 'active' : '' }}">
      <i class="bi bi-person-circle"></i> Mon profil
    </a>

    <!-- ── TOGGLE THÈME ── -->
    <div style="margin-top:auto;">
      <div class="theme-switch-wrap">
        <span class="theme-switch-label">
          <i class="bi bi-moon-stars" id="theme-icon"></i>
          <span id="theme-label">Thème sombre</span>
        </span>
        <button class="toggle-btn" id="theme-toggle" title="Changer le thème" aria-label="Changer le thème"></button>
      </div>

      <!-- Profil + déconnexion -->
      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div style="width:34px;height:34px;background:var(--accent);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
          <div>
            <a href="{{ route('profil') }}" style="font-size:13px;font-weight:600;color:var(--text);text-decoration:none;">{{ auth()->user()->name }}</a>
            <span class="badge-role">{{ ucfirst(auth()->user()->role) }}</span>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger w-100">
            <i class="bi bi-box-arrow-left me-1"></i>Déconnexion
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- ══ MAIN ══ -->
  <div class="main-content">
  <div class="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')" style="color:var(--text);">
        <i class="bi bi-list" style="font-size:20px;"></i>
      </button>
      <span class="topbar-title">@yield('title', 'Tableau de bord')</span>
    </div>

    <div class="d-flex gap-2 align-items-center">

      <!-- ── BARRE DE RECHERCHE GLOBALE ── -->
      <div class="position-relative" id="search-wrapper">
        <div class="input-group input-group-sm">
          <span class="input-group-text" style="background:var(--input-bg);border-color:var(--border);color:var(--muted);">
            <i class="bi bi-search"></i>
          </span>
          <input type="text" id="global-search" placeholder="Rechercher..." autocomplete="off"
                 class="form-control form-control-sm"
                 style="background:var(--input-bg);border-color:var(--border);color:var(--text);width:200px;">
        </div>
        <div id="search-results" style="
          display:none;position:absolute;top:calc(100% + 6px);right:0;
          width:340px;background:var(--card);border:1px solid var(--border);
          border-radius:10px;box-shadow:var(--shadow);z-index:9999;
          max-height:420px;overflow-y:auto;
        "></div>
      </div>
      <!-- ── FIN RECHERCHE ── -->

      <!-- ── CLOCHE NOTIFICATIONS ── -->
      <div class="dropdown">
        <button class="btn notif-bell-btn" type="button" id="notifDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell-fill"></i>
          @if($produitsStockBas->count() > 0)
            <span class="badge rounded-pill bg-danger notif-badge">
              {{ $produitsStockBas->count() }}
            </span>
          @endif
        </button>

        <ul class="dropdown-menu dropdown-menu-end notif-dropdown" aria-labelledby="notifDropdown">
          <li class="notif-header">⚠️ Stock bas</li>
          @if($produitsStockBas->count() > 0)
            @foreach($produitsStockBas as $produit)
              <li>
                <a class="dropdown-item notif-item" href="{{ route('produits.index') }}">
                  <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $produit->nom }}</span>
                    <span class="badge {{ $produit->quantite_stock == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                      {{ $produit->quantite_stock }} {{ $produit->quantite_stock == 0 ? '(rupture)' : 'restant(s)' }}
                    </span>
                  </div>
                </a>
              </li>
            @endforeach
          @else
            <li><span class="dropdown-item-text text-muted">Aucune alerte pour le moment ✅</span></li>
          @endif
        </ul>
      </div>
      <!-- ── FIN CLOCHE ── -->

      @if(auth()->user()->isAdmin())
      <a href="{{ route('produits.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Ajouter
      </a>
      @endif

    </div>
  </div>

  <div class="page-body">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const html       = document.documentElement;
    const toggleBtn  = document.getElementById('theme-toggle');
    const themeLabel = document.getElementById('theme-label');
    const themeIcon  = document.getElementById('theme-icon');

    function applyTheme(theme) {
      html.setAttribute('data-theme', theme);
      localStorage.setItem('Inventix_theme', theme);
      if (theme === 'dark') {
        themeLabel.textContent = 'Thème sombre';
        themeIcon.className    = 'bi bi-moon-stars';
      } else {
        themeLabel.textContent = 'Thème clair';
        themeIcon.className    = 'bi bi-sun';
      }
    }

    const saved = localStorage.getItem('Inventix_theme') || 'dark';
    applyTheme(saved);

    toggleBtn.addEventListener('click', () => {
      const current = html.getAttribute('data-theme');
      applyTheme(current === 'dark' ? 'light' : 'dark');
    });
  </script>

  @yield('scripts')

  <script>
  (function () {
      const input   = document.getElementById('global-search');
      const results = document.getElementById('search-results');
      if (!input) return;
      let timer = null;
      input.addEventListener('input', function () {
          clearTimeout(timer);
          const q = this.value.trim();
          if (q.length < 2) { results.style.display = 'none'; results.innerHTML = ''; return; }
          timer = setTimeout(() => {
              fetch('/recherche?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
              .then(r => r.json())
              .then(data => {
                  if (data.length === 0) {
                      results.innerHTML = '<div style="padding:16px;color:var(--muted);text-align:center;font-size:13px;">Aucun résultat pour <strong>' + q + '</strong></div>';
                      results.style.display = 'block'; return;
                  }
                  const groupes = {};
                  data.forEach(item => { if (!groupes[item.categorie]) groupes[item.categorie] = []; groupes[item.categorie].push(item); });
                  let html = '';
                  for (const [cat, items] of Object.entries(groupes)) {
                      html += `<div style="padding:6px 14px 2px;font-size:10px;text-transform:uppercase;color:var(--muted);letter-spacing:.05em;">${cat}</div>`;
                      items.forEach(item => {
                          html += `<a href="${item.url}" style="display:flex;align-items:center;gap:10px;padding:8px 14px;color:var(--text);text-decoration:none;"
                             onmouseover="this.style.background='var(--table-hover)'"
                             onmouseout="this.style.background='transparent'">
                              <i class="bi ${item.icone}" style="font-size:15px;color:var(--muted);flex-shrink:0;"></i>
                              <div><div style="font-size:13px;font-weight:500;">${item.label}</div>
                              ${item.sous ? `<div style="font-size:11px;color:var(--muted);">${item.sous}</div>` : ''}</div>
                          </a>`;
                      });
                  }
                  results.innerHTML = html; results.style.display = 'block';
              });
          }, 250);
      });
      document.addEventListener('click', function (e) {
          if (!document.getElementById('search-wrapper').contains(e.target)) results.style.display = 'none';
      });
      input.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') { results.style.display = 'none'; input.blur(); }
      });
  })();
  </script>

</body>
</html>