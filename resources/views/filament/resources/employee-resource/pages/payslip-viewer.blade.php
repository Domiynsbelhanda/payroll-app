<x-filament::page>
    <h2 class="text-xl font-bold mb-4">
        Fiches de paie de {{ $record->full_name }}
    </h2>

@if ($payslips->isEmpty())
        <div class="text-gray-500 dark:text-gray-400">Aucune fiche de paie disponible.</div>
    @else
        <div class="overflow-auto rounded-lg shadow mt-6">
            <table class="min-w-full text-sm border bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-2 border">Mois</th>
                    <th class="p-2 border">Année</th>
                    <th class="p-2 border">Jours</th>
                    <th class="p-2 border">Minutes</th>
                    <th class="p-2 border">Salaire de base</th>
                    <th class="p-2 border">Bonus</th>
                    <th class="p-2 border font-bold">Salaire net</th>
                    <th class="p-2 border">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payslips as $slip)
                    <tr class="border-t border-gray-200 dark:border-gray-700">
                        <td class="p-2 border">{{ $slip->month }}</td>
                        <td class="p-2 border">{{ $slip->year }}</td>
                        <td class="p-2 border">{{ $slip->worked_days }}</td>
                        <td class="p-2 border">{{ $slip->worked_minutes }}</td>
                        <td class="p-2 border">{{ number_format($slip->base_salary, 2) }}</td>
                        <td class="p-2 border">{{ number_format($slip->bonus, 2) }}</td>
                        <td class="p-2 border font-bold text-green-600 dark:text-green-400">
                            {{ number_format($slip->net_salary, 2) }}
                        </td>
                        <td class="p-2 border text-center">
                            <a href="{{ route('fiche-paie.pdf', $slip->id) }}" target="_blank" class="text-blue-600 underline">
                                Télécharger PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament::page>
