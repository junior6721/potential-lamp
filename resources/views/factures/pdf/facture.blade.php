<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 13px; }
        .header {
            display: table; width: 100%;
            border-bottom: 3px solid #333; padding-bottom: 12px; margin-bottom: 20px;
        }
        .header-left, .header-right { display: table-cell; vertical-align: top; }
        .header-right { text-align: right; }
        .header h1 { margin: 0; font-size: 20px; }
        .header .numero { font-size: 16px; font-weight: bold; color: #1a73e8; }

        .infos-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .infos-table td { vertical-align: top; padding: 10px; border: 1px solid #ddd; width: 50%; }
        .infos-table .label { font-size: 10px; text-transform: uppercase; color: #888; margin-bottom: 4px; }

        table.lignes { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.lignes th, table.lignes td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.lignes th { background-color: #f0f0f0; }
        .text-end { text-align: right; }
        .total-row td { font-weight: bold; background-color: #f7f7f7; }

        .recap-paiement {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .recap-paiement td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        .recap-paiement .label-cell { background-color: #f7f7f7; font-weight: bold; width: 60%; }
        .statut-payee { color: #1a8a3b; font-weight: bold; }
        .statut-impayee { color: #c0392b; font-weight: bold; }
        .statut-partiel { color: #d97706; font-weight: bold; }

        .footer { margin-top: 40px; font-size: 10px; color: #888; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>StockPro</h1>
            <div style="color:#888; font-size:11px;">Facture</div>
        </div>
        <div class="header-right">
            <div class="numero">{{ $facture->numero }}</div>
            <div style="color:#888; font-size:11px;">
                Date : {{ $facture->date_facture->format('d/m/Y') }}<br>
                Commande : {{ $facture->commande->numero }}
            </div>
        </div>
    </div>

    <table class="infos-table">
        <tr>
            <td>
                <div class="label">Facturé à</div>
                <strong>{{ $facture->commande->tiers?->societe ?: $facture->commande->tiers?->contact ?: '—' }}</strong><br>                
                @if($facture->commande->tiers)
                    {{ $facture->commande->tiers->contact ?? '' }}<br>
                    {{ $facture->commande->tiers->telephone ?? '' }}<br>
                    {{ $facture->commande->tiers->email ?? '' }}
                @endif
            </td>
            <td>
                <div class="label">Statut du paiement</div>
                <span class="statut-{{ $facture->statut_paiement === 'payee' ? 'payee' : ($facture->statut_paiement === 'impayee' ? 'impayee' : 'partiel') }}">
                    {{ $facture->statut_paiement_label }}
                </span>
            </td>
        </tr>
    </table>

    <table class="lignes">
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-end">Quantité</th>
                <th class="text-end">Prix unitaire</th>
                <th class="text-end">Sous-total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->commande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                    <td class="text-end">{{ $ligne->quantite }}</td>
                    <td class="text-end">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                    <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-end">MONTANT TOTAL</td>
                <td class="text-end">{{ number_format($facture->montant_total, 0, ',', ' ') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="recap-paiement">
        <tr>
            <td class="label-cell">Montant total de la facture</td>
            <td class="text-end">{{ number_format($facture->montant_total, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td class="label-cell">Montant déjà payé</td>
            <td class="text-end">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td class="label-cell">Reste à payer</td>
            <td class="text-end">{{ number_format($facture->reste_a_payer, 0, ',', ' ') }}</td>
        </tr>
    </table>

    @if($facture->paiements->count() > 0)
        <table class="lignes" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Mode</th>
                    <th class="text-end">Montant</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facture->paiements as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                        <td>{{ $paiement->mode_paiement_label }}</td>
                        <td class="text-end">{{ number_format($paiement->montant, 0, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }} — StockPro
    </div>

</body>
</html>
