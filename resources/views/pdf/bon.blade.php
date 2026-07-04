<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a1a2e; }

    .header {
      display: table; width: 100%; margin-bottom: 24px;
      border-bottom: 3px solid {{ $mouvement->type === 'entree' ? '#28a745' : '#dc3545' }};
      padding-bottom: 16px;
    }
    .header-left  { display: table-cell; vertical-align: top; }
    .header-right { display: table-cell; vertical-align: top; text-align: right; }
    .brand { font-size: 22px; font-weight: 700; color: #1a73e8; margin-bottom: 2px; }
    .brand-sub { font-size: 10px; color: #9e9e9e; }

    .bon-title {
      font-size: 18px; font-weight: 700; margin-bottom: 4px;
      color: {{ $mouvement->type === 'entree' ? '#28a745' : '#dc3545' }};
    }
    .bon-num { font-size: 11px; color: #9e9e9e; }

    .section { margin-bottom: 20px; }
    .section-title {
      font-size: 10px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.08em; color: #9e9e9e;
      border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; margin-bottom: 12px;
    }

    .info-grid { display: table; width: 100%; }
    .info-col  { display: table-cell; width: 50%; vertical-align: top; padding-right: 20px; }
    .info-row  { margin-bottom: 10px; }
    .info-label { font-size: 10px; color: #9e9e9e; margin-bottom: 2px; }
    .info-val   { font-size: 13px; font-weight: 600; }

    .product-box {
      background: #f4f6fb; border: 1px solid #e0e7ef;
      border-radius: 8px; padding: 16px 20px; margin-bottom: 20px;
    }
    .product-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
    .product-ref  { font-size: 11px; color: #9e9e9e; }

    .qty-box {
      display: table; width: 100%; margin-bottom: 20px;
    }
    .qty-cell {
      display: table-cell; width: 33.33%;
      text-align: center; padding: 16px;
      background: #f9fafb; border: 1px solid #e5e7eb;
    }
    .qty-num   { font-size: 28px; font-weight: 700; }
    .qty-label { font-size: 10px; color: #9e9e9e; margin-top: 4px; }

    .signature-zone {
      display: table; width: 100%; margin-top: 40px;
    }
    .signature-col {
      display: table-cell; width: 33.33%;
      text-align: center; padding: 0 10px;
    }
    .signature-line {
      border-top: 1px solid #1a1a2e; margin-top: 40px;
      padding-top: 6px; font-size: 10px; color: #9e9e9e;
    }

    .footer {
      position: fixed; bottom: 20px; left: 20px; right: 20px;
      border-top: 1px solid #e5e7eb; padding-top: 8px;
      font-size: 9px; color: #9e9e9e; display: table; width: 100%;
    }
    .footer-left  { display: table-cell; }
    .footer-right { display: table-cell; text-align: right; }

    .badge-type {
      display: inline-block; padding: 4px 14px; border-radius: 100px;
      font-size: 11px; font-weight: 700;
      background: {{ $mouvement->type === 'entree' ? '#d1fae5' : '#fee2e2' }};
      color: {{ $mouvement->type === 'entree' ? '#065f46' : '#991b1b' }};
    }
  </style>
</head>
<body>

  <!-- EN-TÊTE -->
  <div class="header">
    <div class="header-left">
      <div class="brand">📦 Inventix</div>
      <div class="brand-sub">Système de Gestion de Stock</div>
    </div>
    <div class="header-right">
      <div class="bon-title">
        BON DE {{ strtoupper($mouvement->type === 'entree' ? 'RÉCEPTION' : 'SORTIE') }}
      </div>
      <div class="bon-num">N° {{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</div>
      <div style="margin-top:8px;">
        <span class="badge-type">
          {{ $mouvement->type === 'entree' ? '↓ Entrée en stock' : '↑ Sortie de stock' }}
        </span>
      </div>
    </div>
  </div>

  <!-- PRODUIT -->
  <div class="section">
    <div class="section-title">Produit concerné</div>
    <div class="product-box">
      <div class="product-name">{{ $mouvement->produit->nom ?? '-' }}</div>
      <div class="product-ref">
        Réf : {{ $mouvement->produit->reference ?? '-' }} &nbsp;|&nbsp;
        Catégorie : {{ $mouvement->produit->categorie->nom ?? '-' }} &nbsp;|&nbsp;
        Unité : {{ $mouvement->produit->unite ?? '-' }}
      </div>
    </div>
  </div>

  <!-- QUANTITÉS -->
  <div class="section">
    <div class="section-title">Mouvement de stock</div>
    <div class="qty-box">
      <div class="qty-cell">
        <div class="qty-num" style="color:#9e9e9e;">{{ $mouvement->stock_avant }}</div>
        <div class="qty-label">Stock avant</div>
      </div>
      <div class="qty-cell">
        <div class="qty-num" style="color:{{ $mouvement->type === 'entree' ? '#28a745' : '#dc3545' }};">
          {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
        </div>
        <div class="qty-label">Quantité {{ $mouvement->type === 'entree' ? 'reçue' : 'sortie' }}</div>
      </div>
      <div class="qty-cell">
        <div class="qty-num" style="color:#1a73e8;">{{ $mouvement->stock_apres }}</div>
        <div class="qty-label">Stock après</div>
      </div>
    </div>
  </div>

  <!-- INFORMATIONS -->
  <div class="section">
    <div class="section-title">Informations</div>
    <div class="info-grid">
      <div class="info-col">
        <div class="info-row">
          <div class="info-label">Date et heure</div>
          <div class="info-val">{{ $mouvement->created_at->format('d/m/Y à H:i') }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Opérateur</div>
          <div class="info-val">{{ $mouvement->user->name ?? '-' }}</div>
        </div>
      </div>
      <div class="info-col">
        <div class="info-row">
          <div class="info-label">Référence document</div>
          <div class="info-val">{{ $mouvement->reference_doc ?? 'Aucune' }}</div>
        </div>
        <div class="info-row">
          <div class="info-label">Motif</div>
          <div class="info-val">{{ $mouvement->motif ?? 'Non précisé' }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- SIGNATURES -->
  <div class="signature-zone">
    <div class="signature-col">
      <div class="signature-line">Établi par</div>
    </div>
    <div class="signature-col">
      <div class="signature-line">Vérifié par</div>
    </div>
    <div class="signature-col">
      <div class="signature-line">Approuvé par</div>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="footer">
    <div class="footer-left">Inventix — Bon N° {{ str_pad($mouvement->id, 6, '0', STR_PAD_LEFT) }}</div>
    <div class="footer-right">Document généré le {{ \Carbon\Carbon::now('Africa/Johannesburg')->format('d/m/Y à H:i') }} — {{ $parametres->nom_societe }}</div>
  </div>

</body>
</html>
