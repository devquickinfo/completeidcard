<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistrationFieldValue extends Model
{
    protected $fillable = [
        'event_registration_id',
        'event_custom_field_id',
        'value',
    ];

    public function registration()
    {
        return $this->belongsTo(
            EventRegistration::class,
            'event_registration_id'
        );
    }

    public function customField()
    {
        return $this->belongsTo(
            EventCustomField::class,
            'event_custom_field_id'
        );
    }
}