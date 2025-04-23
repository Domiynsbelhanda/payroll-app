<x-filament::page>

    <div class="mt-4">
        <x-filament::button wire:click="exportPdf" color="secondary">
            Télécharger PDF
        </x-filament::button>
    </div>


    <form wire:submit.prevent="generateReport">
        {{ $this->form }}
        <div class="mt-6">
            <x-filament::button type="submit">
                Générer le rapport
            </x-filament::button>
        </div>
    </form>

    @if($payrollData->isNotEmpty())
        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4">Rapport de paie - {{ $selectedMonth }}/{{ $selectedYear }}</h2>

            <div class="overflow-auto rounded-lg shadow">
                <table class="min-w-full text-sm text-left border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Employé</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Type</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Jours</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Minutes</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Salaire de base</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600">Bonus</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600 font-bold">Salaire net</th>
                        <th class="p-2 border border-gray-300 dark:border-gray-600 text-red-600 dark:text-red-400">Erreur</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    @foreach($payrollData as $row)
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['employee'] }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['type'] ?? '-' }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['worked_days'] ?? '-' }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['worked_minutes'] ?? '-' }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['base_salary'] ?? '-' }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700">{{ $row['bonus'] ?? '-' }}</td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700 font-bold text-green-600 dark:text-green-400">
                                {{ $row['net_salary'] ?? '-' }}
                            </td>
                            <td class="p-2 border border-gray-200 dark:border-gray-700 text-red-600 dark:text-red-400">
                                {{ $row['error'] ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-filament::page>
