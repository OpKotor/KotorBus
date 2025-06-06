<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Godišnji finansijski izvještaj' }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; margin-top: 24px; }
        th, td { border: 1px solid #cccccc; padding: 8px 12px; text-align: left; }
        th { background: #eeeeee; }
    </style>
</head>
<body>
    <h2>{{ $title ?? 'Godišnji finansijski izvještaj' }}</h2>
    <p>Godina: {{ $year }}</p>
    <table>
        <thead>
            <tr>
                <th>Mjesec</th>
                <th>Ukupan prihod</th>
            </tr>
        </thead>
        <tbody>
            @foreach($financeData as $row)
            <tr>
                <td>{{ $row->mjesec }}</td>
                <td>{{ number_format($row->prihod ?? 0, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>