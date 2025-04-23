<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport de Paie</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        th { background-color: #eee; }
    </style>
</head>
<body>
<h2>Rapport de Paie — Mois {{ $month }}/{{ $year }}</h2>

<table>
    <thead>
    <tr>
        <th>Employé</th>
        <th>Type</th>
        <th>Jours</th>
        <th>Minutes</th>
        <th>Salaire de base</th>
        <th>Bonus</th>
        <th>Salaire net</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($data as $row)
        <tr>
            <td>{{ $row['employee'] }}</td>
            <td>{{ $row['type'] ?? '-' }}</td>
            <td>{{ $row['worked_days'] ?? '-' }}</td>
            <td>{{ $row['worked_minutes'] ?? '-' }}</td>
            <td>{{ $row['base_salary'] ?? '-' }}</td>
            <td>{{ $row['bonus'] ?? '-' }}</td>
            <td>{{ $row['net_salary'] ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
