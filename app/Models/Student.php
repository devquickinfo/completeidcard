<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;
use App\Models\Section;

class Student extends Model
{
    protected $fillable = [

        'admission_no',
        'school_id',
        'first_name',
        'last_name',
        'father_name',
        'address',
        'gender',
        'date_of_birth',
        'blood_group',
        'phone',
        'class_id',
        'section_id',
        'photo',
        'capturephoto',
        'capture_background',
        'captured_by_camera',
        'idcardprinted',
        'IsDeleted',
        'mother_name',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'captured_by_camera' => 'boolean'
    ];

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class,'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
