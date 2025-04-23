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
    }
}
