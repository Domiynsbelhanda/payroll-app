<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_type',
        'amount',
        'bonus',
        'overtime_rate',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
