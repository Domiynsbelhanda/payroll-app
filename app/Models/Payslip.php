<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'base_salary',
        'bonus',
        'net_salary',
        'worked_minutes',
        'worked_days',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
