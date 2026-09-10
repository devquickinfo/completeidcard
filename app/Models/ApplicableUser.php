<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicableUser extends Model
{
    protected $table='applicable_user';
    protected $fillable = [
        'type',
    ];
}
