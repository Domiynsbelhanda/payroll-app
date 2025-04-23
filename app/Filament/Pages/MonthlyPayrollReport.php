<?php
namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;


class MonthlyPayrollReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Paie';
    protected static string $view = 'filament.pages.monthly-payroll-report';

    public ?string $selectedMonth = null;
    public ?string $selectedYear = null;
    public Collection $payrollData;

    public function mount(): void
    {
        $this->selectedMonth = now()->format('m');
        $this->selectedYear = now()->format('Y');
        $this->payrollData = collect();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Select::make('selectedMonth')
                    ->label('Mois')
                    ->options([
                        '01' => 'Janvier',
                        '02' => 'Février',
                        '03' => 'Mars',
                        '04' => 'Avril',
                        '05' => 'Mai',
                        '06' => 'Juin',
                        '07' => 'Juillet',
                        '08' => 'Août',
                        '09' => 'Septembre',
                        '10' => 'Octobre',
                        '11' => 'Novembre',
                        '12' => 'Décembre',
                    ])
                    ->required(),

                Forms\Components\Select::make('selectedYear')
                    ->label('Année')
                    ->options(
                        collect(range(date('Y') - 5, date('Y') + 1))
                            ->mapWithKeys(fn ($year) => [$year => $year])
                    )
                    ->required(),
            ]),
        ];
    }

    public function generateReport(): void
    {
        $startDate = "{$this->selectedYear}-{$this->selectedMonth}-01";
        $endDate = now()->parse($startDate)->endOfMonth()->toDateString();

        $this->payrollData = Employee::with([
            'salarySetting',
            'attendances' => fn ($q) => $q->whereBetween('date', [$startDate, $endDate])
        ])->get()->map(function ($employee) {
            $setting = $employee->salarySetting;
            $attendances = $employee->attendances;

            $totalMinutes = $attendances->sum('worked_minutes');
            $totalDays = $attendances->count();

            if (!$setting) {
                return [
                    'employee' => $employee->full_name,
                    'error' => 'Aucun paramètre de salaire configuré.',
                ];
            }

            $baseSalary = match ($setting->salary_type) {
                'fixed' => $setting->amount,
                'hourly' => ($totalMinutes / 60) * $setting->amount,
                'daily' => $totalDays * $setting->amount,
            };

            $netSalary = $baseSalary + $setting->bonus;

            return [
                'employee' => $employee->full_name,
                'type' => $setting->salary_type,
                'worked_minutes' => $totalMinutes,
                'worked_days' => $totalDays,
                'base_salary' => number_format($baseSalary, 2),
                'bonus' => number_format($setting->bonus, 2),
                'net_salary' => number_format($netSalary, 2),
            ];
        });
    }

    public function exportPdf()
    {
        $startDate = "{$this->selectedYear}-{$this->selectedMonth}-01";
        $endDate = now()->parse($startDate)->endOfMonth()->toDateString();

        $payrollData = \App\Models\Employee::with(['salarySetting', 'attendances' => fn ($query) =>
        $query->whereBetween('date', [$startDate, $endDate])
        ])->get()->map(function ($employee) {
            $setting = $employee->salarySetting;
            $attendances = $employee->attendances;

            $totalMinutes = $attendances->sum('worked_minutes');
            $totalDays = $attendances->count();

            if (!$setting) {
                return [
                    'employee' => $employee->full_name,
                    'error' => 'Aucune configuration de salaire',
                ];
            }

            $baseSalary = match ($setting->salary_type) {
                'fixed' => $setting->amount,
                'hourly' => ($totalMinutes / 60) * $setting->amount,
                'daily' => $totalDays * $setting->amount,
            };

            $netSalary = $baseSalary + $setting->bonus;

            return [
                'employee' => $employee->full_name,
                'type' => $setting->salary_type,
                'worked_minutes' => $totalMinutes,
                'worked_days' => $totalDays,
                'base_salary' => number_format($baseSalary, 2),
                'bonus' => number_format($setting->bonus, 2),
                'net_salary' => number_format($netSalary, 2),
            ];
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payroll-report', [
            'data' => $payrollData,
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear,
        ]);

        return \Illuminate\Support\Facades\Response::streamDownload(
            fn () => print($pdf->stream()),
            'rapport-paie-'.$this->selectedMonth.'-'.$this->selectedYear.'.pdf'
        );
    }


}
