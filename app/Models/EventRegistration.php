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
    ];

    public function event()
    {
        return $this->belongsTo(ManageEvent::class, 'event_id');
    }
}
