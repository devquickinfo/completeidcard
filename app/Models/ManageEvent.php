<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageEvent extends Model
{
    protected $fillable = [
        'event_name',
        'start_date',
        'end_date',
        'address',
        'contact_person1',
        'organizer_name',
        'description',
        'unique_code',
        'logo',
        'vendor_id',
    ];

    public function registrations()
    {
        return $this->hasMany(
            EventRegistration::class,
            'event_id'
        );
    }

    public function customFields()
    {
        return $this->hasMany(
            EventCustomField::class,
            'event_id'
        )
        ->where('is_deleted', false)
        ->orderBy('sort_order')
        ->orderBy('id');
    }
}