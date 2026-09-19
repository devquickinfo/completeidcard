<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mainidcard;
use App\Models\Teacher;

class School extends Model
{
    protected $fillable = [

        'school_name',
        'school_code',
        'email',
        'principal_name',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'logo',
        'status',
        'principal_signature',
        'IsDeleted',
        'vendor_id',
        'student_limit',

    ];
    public function mainidcards()
    {
        return $this->hasMany(Mainidcard::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'school_id', 'id');
    }
}
