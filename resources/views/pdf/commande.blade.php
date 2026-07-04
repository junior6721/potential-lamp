<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; font-size: 13px; }
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-left, .header-right { display: table-cell; vertical-align: top; }
        .header-right { text-align: right; }
        .header h1 { margin: 0; font-size: 22px; }
        .numero { font-size: 16px; font-weight: bold; color: #1a73e8; }

        .infos-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .infos-grid td {
            vertical-align: top;
            padding: 10px;
            border: 1px solid #ddd;
            width: 50%;
        }
        .infos-grid .label { font-size: 11px; color: #888; text-transform: uppercase; }
        .infos-grid .valeur { font-size: 14px; font-weight: bold; margin-top: 3px; }

        table.lignes {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.lignes th, table.lignes td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.lignes th { background-color: #f0f0f0; font-size: 11px; text-transform: uppercase; }
        table.lignes .text-end { text-align: right; }
        table.lignes tfoot td { font-weight: bold; background-color: #f7f7f7; }

        .notes-box {
            margin-top: 20px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fafafa;
        }
        .notes-box .label { font-size: 11px; color: #888; text-transform: uppercase; margin-bottom: 4px; }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #999;
            font-size: 12px;
            color: #555;
        }

        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <h1>Bon de commande</h1>
            <div>{{ $commande->type === 'fournisseur' ? 'Commande Fournisseur' : 'Commande Client' }}</div>
        </div>
        <div class="header-right">
            <div class="numero">{{ $commande->numero }}</div>
            <div>Date : {{ $commande->date_commande->format('d/m/Y') }}</div>
        </div>
    </div>

    <table class="infos-grid">
        <tr>
            <td>
                <div class="label">{{ $commande->type === 'fournisseur' ? 'Fournisseur' : 'Client' }}</div>
                <div class="valeur">{{ $commande->tiers->societe ?? '—' }}</div>
                @if($commande->tiers)
                    @if($commande->tiers->contact)<div>Contact : {{ $commande->tiers->contact }}</div>@endif
                    @if($commande->tiers->telephone)<div>Tél : {{ $commande->tiers->telephone }}</div>@endif
                    @if($commande->tiers->email)<div>Email : {{ $commande->tiers->email }}</div>@endif
                @endif
            </td>
            <td>
                <div class="label">Statut</div>
                <div class="valeur">{{ $commande->statut_label }}</div>
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
            @foreach($commande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->produit->nom ?? 'Produit supprimé' }}</td>
                    <td class="text-end">{{ $ligne->quantite }}</td>
                    <td class="text-end">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }}</td>
                    <td class="text-end">{{ number_format($ligne->sous_total, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end">Total</td>
                <td class="text-end">{{ number_format($commande->total, 0, ',', ' ') }}</td>
            </tr>
        </tfoot>
    </table>

    @if($commande->notes)
        <div class="notes-box">
            <div class="label">Notes</div>
            <div>{{ $commande->notes }}</div>
        </div>
    @endif

    <table class="signatures">
        <tr>
            <td>Signature {{ $commande->type === 'fournisseur' ? 'du fournisseur' : 'du client' }}</td>
            <td>Signature (Inventix)</td>
        </tr>
    </table>

    <div class="footer">
    Document généré le {{ \Carbon\Carbon::now('Africa/Johannesburg')->format('d/m/Y à H:i') }} — {{ $parametres->nom_societe }}
</body>
</html>
