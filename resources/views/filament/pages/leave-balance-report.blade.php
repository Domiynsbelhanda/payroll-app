<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Solde de congés par employé</h2>

    <div class="overflow-auto shadow rounded">
        <table class="min-w-full text-sm text-left bg-white dark:bg-gray-900 border table-fixed">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100">
            <tr>
                <th class="p-2 border">Employé</th>
                <th class="p-2 border">Congés acquis</th>
                <th class="p-2 border">Utilisés</th>
                <th class="p-2 border">Solde</th>
                <th class="p-2 border">%</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($balances as $row)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="p-2 border">{{ $row['employe'] }}</td>
                    <td class="p-2 border">{{ $row['acquis'] }}</td>
                    <td class="p-2 border">{{ $row['utilises'] }}</td>
                    <td class="p-2 border font-bold">{{ $row['solde'] }}</td>
                    <td class="p-2 border">
                        {{ $row['acquis'] > 0 ? round(($row['utilises'] / $row['acquis']) * 100) : 0 }}%
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-filament::page>
