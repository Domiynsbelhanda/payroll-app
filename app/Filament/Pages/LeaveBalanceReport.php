<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Employee;
use Illuminate\Support\Collection;

class LeaveBalanceReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Présence & Temps';
    protected static ?string $title = 'Solde de Congés';
    protected static string $view = 'filament.pages.leave-balance-report';

    public Collection $balances;

    public function mount(): void
    {
        $this->balances = Employee::with(['salarySetting', 'leaveRequests' => function ($query) {
            $query->where('status', 'approuvé')->where('type', 'congé');
        }])->get()->map(function ($employee) {
            $acquis = $employee->salarySetting->leave_days_per_year ?? 0;
            $utilises = $employee->leaveRequests->sum(function ($req) {
                $start = \Carbon\Carbon::parse($req->start_date);
                $end = \Carbon\Carbon::parse($req->end_date);
                return $start->diffInDays($end) + 1;
            });
            return [
                'employe' => $employee->full_name,
                'acquis' => $acquis,
                'utilises' => $utilises,
                'solde' => max($acquis - $utilises, 0),
            ];
        });
    }
}
