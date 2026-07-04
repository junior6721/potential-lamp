<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1a1a2e; }

    .header {
      background: #1a73e8; color: #fff;
      padding: 16px 20px; margin-bottom: 14px;
      display: table; width: 100%;
    }
    .header-left  { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; }
    .header h1    { font-size: 18px; font-weight: 700; margin-bottom: 2px; }
    .header-sub   { font-size: 10px; opacity: 0.85; }

    .summary { display: table; width: 100%; margin-bottom: 14px; }
    .summary-box {
      display: table-cell; width: 25%; padding: 10px 14px;
      background: #f4f6fb; border: 1px solid #e0e7ef;
      border-radius: 6px; text-align: center;
    }
    .summary-box + .summary-box { border-left: none; }
    .summary-num   { font-size: 20px; font-weight: 700; }
    .summary-label { font-size: 9px; color: #6b7280; margin-top: 2px; }

    table { width: 100%; border-collapse: collapse; }
    thead th {
      background: #1a73e8; color: #fff;
      padding: 7px 9px; font-size: 9.5px;
      text-transform: uppercase; letter-spacing: 0.04em; text-align: left;
    }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody td { padding: 6px 9px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }

    .badge { display: inline-block; padding: 2px 8px; border-radius: 100px; font-size: 9px; font-weight: 600; }
    .badge-entree { background: #d1fae5; color: #065f46; }
    .badge-sortie { background: #fee2e2; color: #991b1b; }

    .footer {
      border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 14px;
      display: table; width: 100%; color: #9e9e9e; font-size: 9px;
    }
    .footer-left  { display: table-cell; }
    .footer-right { display: table-cell; text-align: right; }
  </style>
</head>
<body>

  <div class="header">
    <div class="header-left">
      <h1>↕ Inventix — Historique des Mouvements</h1>
      <div class="header-sub">Période : {{ $date_debut }} → {{ $date_fin }}</div>
    </div>
    <div class="header-right">
      <div style="font-size:10px;">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
      <div style="font-size:9px;opacity:0.7;">Par {{ auth()->user()->name }}</div>
    </div>
  </div>

  <div class="summary">
    <div class="summary-box">
      <div class="summary-num" style="color:#1a73e8;">{{ $mouvements->count() }}</div>
      <div class="summary-label">Total mouvements</div>
    </div>
    <div class="summary-box">
      <div class="summary-num" style="color:#28a745;">{{ $total_entrees }}</div>
      <div class="summary-label">Unités entrées</div>
    </div>
    <div class="summary-box">
      <div class="summary-num" style="color:#dc3545;">{{ $total_sorties }}</div>
      <div class="summary-label">Unités sorties</div>
    </div>
    <div class="summary-box">
      <div class="summary-num" style="color:#6c5ce7;">{{ $mouvements->groupBy('produit_id')->count() }}</div>
      <div class="summary-label">Produits concernés</div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Produit</th>
        <th>Type</th>
        <th style="text-align:center;">Quantité</th>
        <th style="text-align:center;">Stock avant</th>
        <th style="text-align:center;">Stock après</th>
        <th>Motif</th>
        <th>Réf. doc</th>
        <th>Opérateur</th>
      </tr>
    </thead>
    <tbody>
      @forelse($mouvements as $m)
      <tr>
        <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
        <td><strong>{{ $m->produit->nom ?? '-' }}</strong></td>
        <td>
          @if($m->type === 'entree')
            <span class="badge badge-entree">↓ Entrée</span>
          @else
            <span class="badge badge-sortie">↑ Sortie</span>
          @endif
        </td>
        <td style="text-align:center;font-weight:700;">{{ $m->quantite }}</td>
        <td style="text-align:center;color:#9e9e9e;">{{ $m->stock_avant }}</td>
        <td style="text-align:center;font-weight:600;">{{ $m->stock_apres }}</td>
        <td>{{ $m->motif ?? '-' }}</td>
        <td style="color:#9e9e9e;">{{ $m->reference_doc ?? '-' }}</td>
        <td>{{ $m->user->name ?? '-' }}</td>
      </tr>
      @empty
      <tr><td colspan="9" style="text-align:center;padding:20px;color:#9e9e9e;">Aucun mouvement</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    <div class="footer-left">Inventix — Document confidentiel</div>
    <div class="footer-right">
      Entrées : <strong>{{ $total_entrees }}</strong> unités &nbsp;|&nbsp;
      Sorties : <strong>{{ $total_sorties }}</strong> unités
    </div>
  </div>

</body>
</html>
