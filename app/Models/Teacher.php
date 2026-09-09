<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'address',
        'gender',
        'dob',
        'photo',
        'employee_code',
        'father_husband',
        'school_id',
    ];
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }
}
