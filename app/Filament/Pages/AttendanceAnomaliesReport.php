<?php
namespace App\Filament\Pages;

use App\Models\Employee;
use App\Models\Attendance;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Collection;

class AttendanceAnomaliesReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Présence & Temps';
    protected static ?string $title = 'Anomalies de Présence';
    protected static string $view = 'filament.pages.attendance-anomalies-report';

    public ?int $selectedMonth = null;
    public ?int $selectedYear = null;
    public ?int $employeeId = null;

    public Collection $anomalies;

    public function mount(): void
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->employeeId = null;

        $this->generate();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('employeeId')
                ->label('Employé')
                ->options(Employee::all()->pluck('full_name', 'id'))
                ->searchable()
                ->placeholder('Tous les employés'),

            Forms\Components\Select::make('selectedMonth')
                ->label('Mois')
                ->options(array_combine(range(1, 12), range(1, 12)))
                ->required(),

            Forms\Components\Select::make('selectedYear')
                ->label('Année')
                ->options(range(2023, now()->year + 1))
                ->required(),
        ];
    }

    public function generate()
    {
        $start = now()->setMonth($this->selectedMonth)->setYear($this->selectedYear)->startOfMonth()->toDateString();
        $end = now()->setMonth($this->selectedMonth)->setYear($this->selectedYear)->endOfMonth()->toDateString();

        $query = Employee::with(['attendances' => fn ($q) =>
        $q->whereBetween('date', [$start, $end])
        ]);

        if ($this->employeeId) {
            $query->where('id', $this->employeeId);
        }

        $this->anomalies = $query->get()->map(function ($employee) {
            $attendances = $employee->attendances;

            $retards = $attendances->where('is_late', true);
            $retardJours = $retards->count();
            $retardMinutes = $retards->sum('late_minutes');

            $heuresSup = $attendances->filter(fn ($a) => $a->overtime_minutes > 0)->count();
            $absences = $attendances->where('unjustified_absent', true)->count();

            return [
                'employee' => $employee->full_name,
                'retard_days' => $retardJours,
                'retard_minutes' => $retardMinutes,
                'overtime_days' => $heuresSup,
                'unjustified_absences' => $absences,
            ];
        });
    }
}
