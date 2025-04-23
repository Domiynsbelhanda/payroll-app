<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'reason',
        'start_date',
        'end_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
