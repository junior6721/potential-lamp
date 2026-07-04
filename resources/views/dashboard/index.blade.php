@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')

<!-- ── STATS ── -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-num">{{ $stats['total_produits'] }}</div>
          <div class="stat-label">Produits actifs</div>
        </div>
        <div class="stat-icon" style="background:rgba(26,115,232,0.15);">
          <i class="bi bi-box" style="color:#1a73e8;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-num">{{ $stats['total_categories'] }}</div>
          <div class="stat-label">Catégories</div>
        </div>
        <div class="stat-icon" style="background:rgba(108,92,231,0.15);">
          <i class="bi bi-tags" style="color:#6c5ce7;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-num text-warning">{{ $stats['ruptures'] }}</div>
          <div class="stat-label">Alertes stock</div>
        </div>
        <div class="stat-icon" style="background:rgba(255,193,7,0.15);">
          <i class="bi bi-exclamation-triangle" style="color:#ffc107;"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <div class="stat-num" style="font-size:20px;">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} F</div>
          <div class="stat-label">Valeur du stock</div>
        </div>
        <div class="stat-icon" style="background:rgba(40,167,69,0.15);">
          <i class="bi bi-currency-exchange" style="color:#28a745;"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ── GRAPHIQUES ── -->
<div class="row g-3 mb-4">

  <!-- Graphique Entrées vs Sorties -->
  <div class="col-lg-7">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
        <span style="font-size:15px;font-weight:600;">
          <i class="bi bi-bar-chart me-2" style="color:#1a73e8;"></i>Entrées vs Sorties — 7 derniers jours
        </span>
        <div class="d-flex gap-2" style="font-size:12px;">
          <span style="color:#28a745;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Entrées</span>
          <span style="color:#dc3545;"><i class="bi bi-circle-fill" style="font-size:8px;"></i> Sorties</span>
        </div>
      </div>
      <div class="card-body p-3">
        <canvas id="chartMouvements" height="220"></canvas>
      </div>
    </div>
  </div>

  <!-- Graphique Top 5 produits -->
  <div class="col-lg-5">
    <div class="card h-100">
      <div class="card-header py-3 px-4">
        <span style="font-size:15px;font-weight:600;">
          <i class="bi bi-trophy me-2" style="color:#ffc107;"></i>Top 5 produits mouvementés
        </span>
      </div>
      <div class="card-body p-3 d-flex align-items-center justify-content-center">
        @if(count($graphique_top['data']) > 0)
          <canvas id="chartTop" height="220"></canvas>
        @else
          <div class="text-center" style="color:#9e9e9e;">
            <i class="bi bi-bar-chart" style="font-size:36px;display:block;margin-bottom:10px;"></i>
            Aucune donnée disponible
          </div>
        @endif
      </div>
    </div>
  </div>

</div>

<!-- ── TABLEAU + ALERTES ── -->
<div class="row g-3">

  <!-- Derniers mouvements -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center py-3 px-4">
        <span style="font-size:15px;font-weight:600;">
          <i class="bi bi-clock-history me-2"></i>Derniers mouvements
        </span>
        <a href="{{ route('mouvements.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
      </div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead><tr>
            <th>Produit</th><th>Type</th><th>Qté</th><th>Stock après</th><th>Par</th><th>Date</th>
          </tr></thead>
          <tbody>
            @forelse($derniers_mouvements as $m)
            <tr>
              <td>{{ $m->produit->nom ?? '-' }}</td>
              <td>
                @if($m->type === 'entree')
                  <span class="badge-entree"><i class="bi bi-arrow-down-circle me-1"></i>Entrée</span>
                @else
                  <span class="badge-sortie"><i class="bi bi-arrow-up-circle me-1"></i>Sortie</span>
                @endif
              </td>
              <td><strong>{{ $m->quantite }}</strong></td>
              <td>{{ $m->stock_apres }}</td>
              <td>{{ $m->user->name ?? '-' }}</td>
              <td style="color:#9e9e9e;font-size:12px;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-4" style="color:#9e9e9e;">Aucun mouvement enregistré</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Alertes rupture -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header py-3 px-4">
        <span style="font-size:15px;font-weight:600;">
          <i class="bi bi-exclamation-triangle text-warning me-2"></i>Alertes de stock
        </span>
      </div>
      <div class="card-body px-4 py-2">
        @forelse($alertes as $p)
        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid rgba(255,255,255,0.06);">
          <div>
            <div style="font-size:14px;font-weight:500;">{{ $p->nom }}</div>
            <div style="font-size:12px;color:#9e9e9e;">{{ $p->categorie->nom ?? '' }}</div>
          </div>
          <div class="text-end">
            <span class="badge-alerte">{{ $p->quantite_stock }} {{ $p->unite }}</span>
            <div style="font-size:11px;color:#9e9e9e;">min: {{ $p->stock_minimum }}</div>
          </div>
        </div>
        @empty
        <div class="text-center py-4" style="color:#9e9e9e;">
          <i class="bi bi-check-circle text-success" style="font-size:28px;display:block;margin-bottom:8px;"></i>
          Aucune alerte !
        </div>
        @endforelse
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  // Données depuis PHP
  const labels  = @json($graphique_mouvements['labels']);
  const entrees = @json($graphique_mouvements['entrees']);
  const sorties = @json($graphique_mouvements['sorties']);
  const topLabels = @json($graphique_top['labels']);
  const topData   = @json($graphique_top['data']);

  // Defaults Chart.js
  Chart.defaults.color = '#9e9e9e';
  Chart.defaults.font.family = "'Segoe UI', sans-serif";
  Chart.defaults.font.size = 12;

  // ── GRAPHIQUE 1 : Entrées vs Sorties (barres groupées) ──
  new Chart(document.getElementById('chartMouvements'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Entrées',
          data: entrees,
          backgroundColor: 'rgba(40,167,69,0.7)',
          borderColor: '#28a745',
          borderWidth: 1,
          borderRadius: 6,
        },
        {
          label: 'Sorties',
          data: sorties,
          backgroundColor: 'rgba(220,53,69,0.7)',
          borderColor: '#dc3545',
          borderWidth: 1,
          borderRadius: 6,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a1a1a',
          borderColor: 'rgba(255,255,255,0.1)',
          borderWidth: 1,
          padding: 10,
          callbacks: {
            label: ctx => ` ${ctx.dataset.label} : ${ctx.parsed.y} unité(s)`
          }
        }
      },
      scales: {
        x: {
          grid: { color: 'rgba(255,255,255,0.05)' },
          ticks: { color: '#9e9e9e' }
        },
        y: {
          grid: { color: 'rgba(255,255,255,0.05)' },
          ticks: { color: '#9e9e9e', stepSize: 1 },
          beginAtZero: true
        }
      }
    }
  });

  // ── GRAPHIQUE 2 : Top 5 produits (donut) ──
  @if(count($graphique_top['data']) > 0)
  const colors = [
    'rgba(26,115,232,0.8)',
    'rgba(40,167,69,0.8)',
    'rgba(255,193,7,0.8)',
    'rgba(220,53,69,0.8)',
    'rgba(108,92,231,0.8)',
  ];

  new Chart(document.getElementById('chartTop'), {
    type: 'doughnut',
    data: {
      labels: topLabels,
      datasets: [{
        data: topData,
        backgroundColor: colors,
        borderColor: '#0f0f0f',
        borderWidth: 3,
        hoverOffset: 8,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#9e9e9e',
            padding: 12,
            font: { size: 11 },
            boxWidth: 12,
            usePointStyle: true,
          }
        },
        tooltip: {
          backgroundColor: '#1a1a1a',
          borderColor: 'rgba(255,255,255,0.1)',
          borderWidth: 1,
          padding: 10,
          callbacks: {
            label: ctx => ` ${ctx.label} : ${ctx.parsed} mouvement(s)`
          }
        }
      }
    }
  });
  @endif
</script>
@endsection