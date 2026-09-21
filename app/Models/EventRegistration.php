<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'mobile',
        'organization',
        'address',
        'ip_address',
        'device_name',
        'event_code',
        'photo',
        'user_unique_code',
    ];

    public function event()
    {
        return $this->belongsTo(
            ManageEvent::class,
            'event_id'
        );
    }

    public function customFieldValues()
    {
        return $this->hasMany(
            EventRegistrationFieldValue::class,
            'event_registration_id'
        );
    }
}