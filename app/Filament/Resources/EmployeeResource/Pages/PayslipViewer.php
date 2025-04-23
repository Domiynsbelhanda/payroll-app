<?php
namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Models\Payslip;
use App\Models\Attendance;
use App\Models\Employee;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Illuminate\Support\Carbon;

class PayslipViewer extends Page
{
    use InteractsWithRecord;

    protected static string $resource = \App\Filament\Resources\EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.payslip-viewer';

    public $payslips;

    public function mount($record): void
    {
        $this->record = Employee::with('salarySetting')->findOrFail($record);
        $this->checkAndGenerateLastMonthPayslip();
        $this->loadPayslips();
    }

    protected function checkAndGenerateLastMonthPayslip(): void
    {
        $lastMonth = now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->format('m');

        // Vérifie si la fiche existe déjà
        $exists = Payslip::where('employee_id', $this->record->id)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();

        if (!$exists) {
            $attendances = Attendance::where('employee_id', $this->record->id)
                ->whereBetween('date', [$lastMonth->startOfMonth()->toDateString(), $lastMonth->endOfMonth()->toDateString()])
                ->get();

            $workedMinutes = $attendances->sum('worked_minutes');
            $workedDays = $attendances->count();

            $setting = $this->record->salarySetting;
            if (!$setting) return;

            $base = match ($setting->salary_type) {
                'fixed' => $setting->amount,
                'hourly' => ($workedMinutes / 60) * $setting->amount,
                'daily' => $workedDays * $setting->amount,
            };

            Payslip::create([
                'employee_id' => $this->record->id,
                'month' => $month,
                'year' => $year,
                'base_salary' => $base,
                'bonus' => $setting->bonus,
                'net_salary' => $base + $setting->bonus,
                'worked_minutes' => $workedMinutes,
                'worked_days' => $workedDays,
            ]);
        }
    }

    protected function loadPayslips(): void
    {
        $this->payslips = Payslip::where('employee_id', $this->record->id)
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
    }
}
