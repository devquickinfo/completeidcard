<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table='history';
	protected $fillable = [
	    'student_id',
	    'change',
	    'description',
	    'class_id',
	    'school_id',
	];
}
