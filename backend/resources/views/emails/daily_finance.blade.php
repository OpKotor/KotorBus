<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dnevni finansijski izvještaj</title>
</head>
<body>
    <h2>Dnevni finansijski izvještaj</h2>
    <p>Datum: {{ $date }}</p>
    <p>Ukupan prihod: <strong>{{ number_format($total, 2, ',', '.') }}€</strong></p>
    <p>U prilogu ovog emaila nalazi se PDF izvještaj.</p>
</body>
</html>