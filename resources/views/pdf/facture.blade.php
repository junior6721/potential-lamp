<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1a1a2e;
            font-size: 11px;
            background: #fff;
        }

        /* ── EN-TÊTE ── */
        .header-table { width: 100%; border-collapse: collapse; padding: 28px 32px 20px; }
        .header-table td { vertical-align: top; padding: 28px 32px 10px; }
        .header-table .right { text-align: right; }

        .logo-img { max-height: 75px; max-width: 170px; display: block; margin-bottom: 8px; }
        .nom-societe { font-size: 16px; font-weight: 700; color: #1a73e8; margin-top: 4px; }

        .facture-title {
            font-size: 52px; font-weight: 700;
            color: #1a73e8; line-height: 1;
            margin-bottom: 6px; letter-spacing: -1px;
        }
        .facture-numero { font-size: 12px; color: #555; }

        /* ── INFOS CLIENT + DATES ── */
        .infos-table { width: 100%; border-collapse: collapse; padding: 0 32px; }
        .infos-table td { padding: 10px 32px; vertical-align: top; }
        .infos-table .right { text-align: left; }

        .label-small { font-size: 10px; color: #888; margin-bottom: 4px; }
        .client-name { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 3px; }
        .client-detail { font-size: 10.5px; color: #555; line-height: 1.7; }

        .date-label { font-size: 10px; color: #888; }
        .date-value { font-size: 11px; color: #1a1a2e; margin-bottom: 10px; }
        .total-label { font-size: 10px; color: #888; margin-bottom: 2px; }
        .total-value { font-size: 26px; font-weight: 700; color: #1a73e8; }

        /* ── SÉPARATEUR ── */
        .separator { border: none; border-top: 2px solid #1a73e8; margin: 0 32px 16px; }

        /* ── TABLEAU PRODUITS ── */
        .body { padding: 0 32px; }

        .produits-table { width: 100%; border-collapse: collapse; }
        .produits-table thead td {
            background: #1a73e8; color: #fff;
            padding: 10px 10px; font-weight: 700;
            font-size: 10px; text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .produits-table thead td:first-child { border-radius: 4px 0 0 4px; }
        .produits-table thead td:last-child  { border-radius: 0 4px 4px 0; }
        .produits-table tbody tr td {
            padding: 11px 10px;
            border-bottom: 1px solid #e8edf5;
            font-size: 11px; color: #333;
            vertical-align: middle;
        }
        .produits-table tbody tr:nth-child(even) td { background: #f7f9ff; }
        .produits-table tfoot td {
            padding: 10px 10px;
            font-weight: 700; font-size: 12px;
            background: #f0f4ff;
            border-top: 2px solid #1a73e8;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .num-col { color: #1a73e8; font-weight: 700; width: 6%; }

        /* ── BAS DE PAGE ── */
        .bottom-section { padding: 16px 32px 0; }
        .bottom-table { width: 100%; border-collapse: collapse; }
        .bottom-table td { vertical-align: top; padding-right: 20px; }
        .bottom-table td:last-child { padding-right: 0; text-align: right; }

        .payment-title { font-size: 11px; font-weight: 700; color: #1a73e8; margin-bottom: 6px; }
        .payment-detail { font-size: 10px; color: #555; line-height: 1.8; }
        .merci { font-size: 10px; color: #555; line-height: 1.7; font-style: italic; max-width: 220px; text-align: right; }

        /* ── VAGUE + FOOTER ── */
        .wave-section { margin-top: 20px; }
        .wave-svg { display: block; width: 100%; }
        .footer-band {
            background: #1a73e8; color: #fff;
            padding: 10px 32px;
            font-size: 9px;
        }
        .footer-inner { width: 100%; border-collapse: collapse; }
        .footer-inner td { color: rgba(255,255,255,0.85); font-size: 9px; vertical-align: middle; }
        .footer-inner .right { text-align: right; }
    </style>
</head>
<body>

    <!-- ── EN-TÊTE : LOGO GAUCHE / TITRE DROITE ── -->
    <table class="header-table">
        <tr>
            <td style="width:45%;">
                @if($parametres->logo)
                    <img src="{{ storage_path('app/public/' . $parametres->logo) }}"
                         alt="Logo" class="logo-img">
                @endif
                <div class="nom-societe">{{ $parametres->nom_societe }}</div>
            </td>
            <td class="right" style="width:55%;">
                <div class="facture-title">Facture</div>
                <div class="facture-numero">N° {{ $facture->numero }}</div>
            </td>
        </tr>
    </table>

    <hr class="separator">

    <!-- ── INFOS CLIENT / DATES / TOTAL DÛ ── -->
    <table class="infos-table">
        <tr>
            <td style="width:40%;">
                <div class="label-small">Facture à :</div>
                <div class="client-name">
                    {{ $facture->commande->tiers?->societe ?: $facture->commande->tiers?->contact ?: '—' }}
                </div>
                <div class="client-detail">
                    @if($facture->commande->tiers?->contact && $facture->commande->tiers?->societe)
                        {{ $facture->commande->tiers->contact }}<br>
                    @endif
                    @if($facture->commande->tiers?->email){{ $facture->commande->tiers->email }}<br>@endif
                    @if($facture->commande->tiers?->telephone){{ $facture->commande->tiers->telephone }}<br>@endif
                    @if($facture->commande->tiers?->adresse){{ $facture->commande->tiers->adresse }}@endif
                </div>
            </td>
            <td style="width:30%;">
                <div class="date-label">Date de facturation :</div>
                <div class="date-value">{{ $facture->date_facture->format('d M Y') }}</div>

                <div class="date-label">Commande liée :</div>
                <div class="date-value">{{ $facture->commande->numero }}</div>

                @php
                    $statutColor = match($facture->statut_paiement) {
                        'payee'               => '#1a8a3b',
                        'partiellement_payee' => '#d97706',
                        default               => '#c0392b',
                    };
                @endphp
                <div class="date-label">Statut :</div>
                <div style="font-weight:700; color:{{ $statutColor }}; font-size:11px;">
                    {{ strtoupper($facture->statut_paiement_label) }}
                </div>
            </td>
            <td style="width:30%; text-align:right;">
                <div class="total-label">Montant total dû :</div>
                <div class="total-value">{{ number_format($facture->reste_a_payer, 0, ',', ' ') }} <span style="font-size:14px;">XOF</span></div>

                @if($facture->montant_paye > 0)
                    <div style="font-size:10px; color:#1a8a3b; margin-top:4px;">
                        Déjà payé : {{ number_format($facture->montant_paye, 0, ',', ' ') }} XOF
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <br>

    <!-- ── TABLEAU PRODUITS ── -->
    <div class="body">
        <table class="produits-table">
            <thead>
                <tr>
                    <td class="num-col text-center" style="color:#fff;">N°</td>
                    <td style="width:38%;">Désignation</td>
                    <td class="text-right">Prix unitaire</td>
                    <td class="text-center">Qté</td>
                    <td class="text-center">Unité</td>
                    <td class="text-right">Montant XOF</td>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->commande->lignes as $index => $ligne)
                    <tr>
                        <td class="num-col text-center">{{ $index + 1 }}</td>
                        <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                        <td class="text-right">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                        <td class="text-center">{{ $ligne->quantite }}</td>
                        <td class="text-center">{{ $ligne->produit->unite ?? '—' }}</td>
                        <td class="text-right"><strong>{{ number_format($ligne->sous_total, 0, ',', ' ') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td class="text-right" style="color:#1a73e8;">TOTAL GÉNÉRAL</td>
                    <td class="text-right" style="color:#1a73e8;">{{ number_format($facture->montant_total, 0, ',', ' ') }} XOF</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <br>

    <!-- ── BAS DE PAGE : PAIEMENT + MERCI ── -->
    <div class="bottom-section">
        <table class="bottom-table">
            <tr>
                <td style="width:50%;">
                    @if($facture->paiements->count() > 0)
                        <div class="payment-title">Paiements reçus</div>
                        @foreach($facture->paiements as $p)
                            <div class="payment-detail">
                                • {{ $p->date_paiement->format('d/m/Y') }}
                                — {{ $p->mode_paiement_label }}
                                — <strong>{{ number_format($p->montant, 0, ',', ' ') }} XOF</strong>
                            </div>
                        @endforeach
                    @else
                        <div class="payment-title">Conditions de paiement</div>
                        <div class="payment-detail">
                            Paiement à réception de facture.<br>
                            Modes acceptés : Espèces, Virement,<br>
                            Mobile Money, Chèque.
                        </div>
                    @endif
                </td>
                <td style="width:50%;">
                    @if($parametres->cachet)
                        <div style="text-align:right; margin-bottom:6px;">
                            <img src="{{ storage_path('app/public/' . $parametres->cachet) }}"
                                 style="max-height:65px; max-width:100px;">
                        </div>
                    @endif
                    <div class="merci">
                        Merci pour votre confiance.<br>
                        Nous sommes fiers de faire partie<br>
                        de votre parcours.
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ── VAGUE DÉCORATIVE ── -->
    <div class="wave-section">
        <svg viewBox="0 0 800 60" xmlns="http://www.w3.org/2000/svg" class="wave-svg" preserveAspectRatio="none">
            <path d="M0,30 C150,60 300,0 450,30 C600,60 700,10 800,30 L800,60 L0,60 Z" fill="#1a73e8"/>
        </svg>
        <div class="footer-band">
            <table class="footer-inner">
                <tr>
                    <td>
                        <strong style="font-size:11px; color:#fff;">{{ $parametres->nom_societe }}</strong><br>
                        Système intelligent de gestion de stock
                    </td>
                    <td class="right">
                        @if($parametres->site_web)<span style="color:#fff;">{{ $parametres->site_web }}</span> &nbsp;|&nbsp; @endif
                        @if($parametres->email){{ $parametres->email }} &nbsp;|&nbsp; @endif
                        Généré le {{ \Carbon\Carbon::now('Africa/Johannesburg')->format('d/m/Y à H:i') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
