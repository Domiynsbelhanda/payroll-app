<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche de Paie</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<div class="header">
    <div>
        <div class="title">Fiche de Paie</div>
        <div><strong>Employé :</strong> {{ $employee->full_name }}</div>
        <div><strong>Période :</strong> {{ $payslip->month }}/{{ $payslip->year }}</div>
    </div>
    <div>
        @php
            $photoPath = $employee->profile_photo
                ? public_path('storage/' . $employee->profile_photo)
                : public_path('images/profile.png');
        @endphp
        <img src="{{ $photoPath }}" alt="Photo" width="80" height="80">
    </div>
</div>

<table>
    <tr>
        <th>Poste</th>
        <td>{{ $employee->position }}</td>
    </tr>
    <tr>
        <th>Type de contrat</th>
        <td>{{ $employee->contractType->name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Département</th>
        <td>{{ $employee->department->name ?? '-' }}</td>
    </tr>
    <tr>
        <th>Jours travaillés</th>
        <td>{{ $payslip->worked_days }}</td>
    </tr>
    <tr>
        <th>Minutes travaillées</th>
        <td>{{ $payslip->worked_minutes }}</td>
    </tr>
    <tr>
        <th>Salaire de base</th>
        <td>{{ number_format($payslip->base_salary, 2) }} $</td>
    </tr>
    <tr>
        <th>Bonus</th>
        <td>{{ number_format($payslip->bonus, 2) }} $</td>
    </tr>
    <tr>
        <th><strong>Salaire Net</strong></th>
        <td><strong>{{ number_format($payslip->net_salary, 2) }} $</strong></td>
    </tr>
</table>
</body>
</html>
