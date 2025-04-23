<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'position',
        'department_id',
        'contract_type_id',
        'profile_photo',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function salarySetting()
    {
        return $this->hasOne(SalarySetting::class);
    }

    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }


}
