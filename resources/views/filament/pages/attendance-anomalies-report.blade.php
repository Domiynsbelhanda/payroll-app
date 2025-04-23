<x-filament::page>
    <form wire:submit.prevent="generate">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit">
                Générer le rapport
            </x-filament::button>
        </div>
    </form>

    @if ($anomalies->isNotEmpty())
        <div class="mt-6 overflow-auto rounded shadow border">
            <table class="min-w-full bg-white dark:bg-gray-900 text-sm border">
                <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="p-2 border">Employé</th>
                    <th class="p-2 border">Jours en retard</th>
                    <th class="p-2 border">Minutes en retard</th>
                    <th class="p-2 border">Jours avec heures sup</th>
                    <th class="p-2 border">Absences injustifiées</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($anomalies as $row)
                    <tr class="border-t dark:border-gray-700">
                        <td class="p-2 border">{{ $row['employee'] }}</td>
                        <td class="p-2 border text-red-600">{{ $row['retard_days'] }}</td>
                        <td class="p-2 border text-red-500">{{ $row['retard_minutes'] }}</td>
                        <td class="p-2 border text-blue-600">{{ $row['overtime_days'] }}</td>
                        <td class="p-2 border text-red-700 font-bold">{{ $row['unjustified_absences'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament::page>
