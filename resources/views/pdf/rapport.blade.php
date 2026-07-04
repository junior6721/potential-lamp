<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
            font-size: 13px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header p {
            margin: 4px 0 0;
            text-transform: capitalize;
            color: #555;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .stats-grid td {
            width: 25%;
            text-align: center;
            padding: 14px 8px;
            border: 1px solid #ddd;
        }
        .stats-grid .num {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }
        .stats-grid .label {
            font-size: 11px;
            color: #666;
        }
        .green { color: #1a8a3b; }
        .red { color: #c0392b; }

        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.detail th, table.detail td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.detail th {
            background-color: #f0f0f0;
        }
        .text-end { text-align: right; }
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
        <h1>Rapport mensuel de stock</h1>
        <p>{{ $stats['mois'] }}</p>
    </div>

    <table class="stats-grid">
        <tr>
            <td>
                <span class="num green">+{{ $stats['totalEntrees'] }}</span>
                <span class="label">Entrées du mois</span>
            </td>
            <td>
                <span class="num red">-{{ $stats['totalSorties'] }}</span>
                <span class="label">Sorties du mois</span>
            </td>
            <td>
                <span class="num">{{ $stats['nombreMouvements'] }}</span>
                <span class="label">Mouvements du mois</span>
            </td>
            <td>
                <span class="num">{{ number_format($stats['valeurStock'], 0, ',', ' ') }}</span>
                <span class="label">Valeur du stock actuel</span>
            </td>
        </tr>
    </table>

    <table class="detail">
        <tr>
            <th>Indicateur</th>
            <th class="text-end">Valeur</th>
        </tr>
        <tr>
            <td>Nombre de produits référencés</td>
            <td class="text-end">{{ $stats['nombreProduits'] }}</td>
        </tr>
        <tr>
            <td>Quantité totale en stock</td>
            <td class="text-end">{{ $stats['quantiteTotaleStock'] }}</td>
        </tr>
        <tr>
            <td>Valeur totale du stock</td>
            <td class="text-end">{{ number_format($stats['valeurStock'], 0, ',', ' ') }}</td>
        </tr>
        <tr>
            <td>Solde du mois (entrées - sorties)</td>
            <td class="text-end">{{ $stats['totalEntrees'] - $stats['totalSorties'] }}</td>
        </tr>
    </table>

    <div class="footer">
    Document généré le {{ \Carbon\Carbon::now('Africa/Johannesburg')->format('d/m/Y à H:i') }} — {{ $parametres->nom_societe }}
</body>
</html>
