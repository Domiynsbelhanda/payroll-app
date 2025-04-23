<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'worked_minutes',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::saving(function ($attendance) {
            if ($attendance->check_in && $attendance->check_out) {
                $attendance->worked_minutes = (strtotime($attendance->check_out) - strtotime($attendance->check_in)) / 60;
            }
        });


        static::saving(function ($attendance) {
            // Calcul du temps de travail
            if ($attendance->check_in && $attendance->check_out) {
                $start = \Carbon\Carbon::parse($attendance->check_in);
                $end = \Carbon\Carbon::parse($attendance->check_out);

                $attendance->worked_minutes = $start->diffInMinutes($end);

                // ✅ Retard
                $heureNormale = \Carbon\Carbon::createFromTimeString('08:00');
                if ($start->gt($heureNormale)) {
                    $attendance->is_late = true;
                    $attendance->late_minutes = $heureNormale->diffInMinutes($start);
                } else {
                    $attendance->is_late = false;
                    $attendance->late_minutes = 0;
                }

                // ✅ Heures supplémentaires (au-delà de 8h)
                $attendance->overtime_minutes = max($attendance->worked_minutes - 480, 0);
            }

            // ✅ Absence injustifiée : ni check-in ni demande de congé pour ce jour
            if (!$attendance->check_in && !$attendance->check_out) {
                $leaveExists = \App\Models\LeaveRequest::where('employee_id', $attendance->employee_id)
                    ->where('status', 'approuvé')
                    ->whereDate('start_date', '<=', $attendance->date)
                    ->whereDate('end_date', '>=', $attendance->date)
                    ->exists();

                $attendance->unjustified_absent = !$leaveExists;
            } else {
                $attendance->unjustified_absent = false;
            }
        });
    }
}
