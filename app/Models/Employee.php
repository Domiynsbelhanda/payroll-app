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
}
