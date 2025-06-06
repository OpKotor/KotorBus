<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Dnevni finansijski izvještaj' }}</title>
    <style>
        /* Stilizacija tabele i teksta za bolji prikaz u PDF-u */
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 24px; }
        th, td { border: 1px solid #cccccc; padding: 8px 12px; text-align: left; }
        th { background: #eeeeee; }
    </style>
</head>
<body>
    <!-- Prikaz naslova izvještaja -->
    <h2>{{ $title ?? 'Dnevni finansijski izvještaj' }}</h2>
    <p>Datum: {{ $date }}</p>
    <table>
        <thead>
            <tr>
                <th>Ukupan prihod</th>
                <th>Broj transakcija</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Prikaz ukupne sume i broja transakcija iz prosleđenih podataka -->
                <td>{{ number_format($financeData->ukupno ?? 0, 2, ',', '.') }} €</td>
                <td>{{ $financeData->broj_transakcija ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>