<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

    /* EN-TÊTE */
    .header {
      background: #1a73e8; color: #fff;
      padding: 16px 20px; margin-bottom: 16px;
      display: table; width: 100%;
    }
    .header-left  { display: table-cell; vertical-align: middle; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; }
    .header h1    { font-size: 20px; font-weight: 700; margin-bottom: 2px; }
    .header-sub   { font-size: 11px; opacity: 0.85; }
    .header-date  { font-size: 11px; opacity: 0.85; }

    /* RÉSUMÉ */
    .summary {
      display: table; width: 100%;
      margin-bottom: 16px; border-collapse: separate;
      border-spacing: 8px 0;
    }
    .summary-box {
      display: table-cell; width: 25%;
      background: #f4f6fb; border: 1px solid #e0e7ef;
      border-radius: 8px; padding: 10px 14px;
      text-align: center;
    }
    .summary-num   { font-size: 22px; font-weight: 700; color: #1a73e8; }
    .summary-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
    .summary-num.red    { color: #dc3545; }
    .summary-num.green  { color: #28a745; }

    /* TABLE */
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead th {
      background: #1a73e8; color: #fff;
      padding: 8px 10px; font-size: 10px;
      text-transform: uppercase; letter-spacing: 0.05em;
      text-align: left;
    }
    tbody tr:nth-child(even) { background: #f9fafb; }
    tbody tr:hover { background: #eef2ff; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10.5px; }

    /* BADGES */
    .badge {
      display: inline-block; padding: 2px 8px;
      border-radius: 100px; font-size: 9px; font-weight: 600;
    }
    .badge-ok     { background: #d1fae5; color: #065f46; }
    .badge-alerte { background: #fef3c7; color: #92400e; }
    .badge-cat    { background: #ede9fe; color: #5b21b6; }

    /* FOOTER */
    .footer {
      border-top: 1px solid #e5e7eb; padding-top: 10px;
      display: table; width: 100%; color: #9e9e9e; font-size: 9px;
    }
    .footer-left  { display: table-cell; }
    .footer-right { display: table-cell; text-align: right; }

    /* CATÉGORIE SÉPARATEUR */
    .cat-row td {
      background: #eef2ff; color: #1a73e8;
      font-weight: 700; font-size: 10px;
      padding: 5px 10px; letter-spacing: 0.05em;
      text-transform: uppercase;
    }
  </style>
</head>
<body>

  <!-- EN-TÊTE -->
  <div class="header">
    <div class="header-left">
      <h1>📦 Inventix — État du Stock</h1>
      <div class="header-sub">Liste complète des produits actifs</div>
    </div>
    <div class="header-right">
      <div class="header-date">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
      <div style="font-size:10px;opacity:0.7;">Par {{ auth()->user()->name }}</div>
    </div>
  </div>

  <!-- RÉSUMÉ -->
  <div class="summary">
    <div class="summary-box">
      <div class="summary-num">{{ $produits->count() }}</div>
      <div class="summary-label">Produits actifs</div>
    </div>
    <div class="summary-box">
      <div class="summary-num red">{{ $ruptures }}</div>
      <div class="summary-label">En alerte stock</div>
    </div>
    <div class="summary-box">
      <div class="summary-num">{{ $produits->sum('quantite_stock') }}</div>
      <div class="summary-label">Unités en stock</div>
    </div>
    <div class="summary-box">
      <div class="summary-num green" style="font-size:14px;">{{ number_format($valeur_totale, 0, ',', ' ') }} F</div>
      <div class="summary-label">Valeur totale</div>
    </div>
  </div>

  <!-- TABLE PRODUITS -->
  <table>
    <thead>
      <tr>
        <th>Référence</th>
        <th>Produit</th>
        <th>Catégorie</th>
        <th>Unité</th>
        <th style="text-align:center;">Stock actuel</th>
        <th style="text-align:center;">Stock min.</th>
        <th style="text-align:right;">Prix achat</th>
        <th style="text-align:right;">Prix vente</th>
        <th style="text-align:right;">Valeur stock</th>
        <th style="text-align:center;">Statut</th>
      </tr>
    </thead>
    <tbody>
      @php $currentCat = null; @endphp
      @forelse($produits as $p)
        @if($p->categorie && $p->categorie->nom !== $currentCat)
          @php $currentCat = $p->categorie->nom; @endphp
          <tr class="cat-row">
            <td colspan="10">📁 {{ $currentCat }}</td>
          </tr>
        @endif
        <tr>
          <td><strong>{{ $p->reference }}</strong></td>
          <td>{{ $p->nom }}</td>
          <td><span class="badge badge-cat">{{ $p->categorie->nom ?? '-' }}</span></td>
          <td>{{ $p->unite }}</td>
          <td style="text-align:center;font-weight:700;">{{ $p->quantite_stock }}</td>
          <td style="text-align:center;color:#9e9e9e;">{{ $p->stock_minimum }}</td>
          <td style="text-align:right;">{{ number_format($p->prix_achat, 0, ',', ' ') }} F</td>
          <td style="text-align:right;">{{ number_format($p->prix_vente, 0, ',', ' ') }} F</td>
          <td style="text-align:right;font-weight:700;">{{ number_format($p->quantite_stock * $p->prix_achat, 0, ',', ' ') }} F</td>
          <td style="text-align:center;">
            @if($p->enRuptureDeStock())
              <span class="badge badge-alerte">⚠ Alerte</span>
            @else
              <span class="badge badge-ok">✓ OK</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="10" style="text-align:center;padding:20px;color:#9e9e9e;">Aucun produit</td></tr>
      @endforelse
    </tbody>
  </table>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-left">Inventix — Document confidentiel</div>
    <div class="footer-right">Valeur totale du stock : <strong>{{ number_format($valeur_totale, 0, ',', ' ') }} FCFA</strong></div>
  </div>

</body>
</html>
