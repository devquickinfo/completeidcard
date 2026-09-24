<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCustomField extends Model
{
    protected $fillable = [
    'event_id',
    'label',
    'field_name',
    'input_type',
    'html_id',
    'html_class',
    'options',
    'sort_order',
    'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(ManageEvent::class, 'event_id');
    }

    public function values()
    {
        return $this->hasMany(
            EventRegistrationFieldValue::class,
            'event_custom_field_id'
        );
    }

    public function getOptionsArrayAttribute()
    {
        if (!$this->options) {
            return [];
        }

        return json_decode($this->options, true) ?: [];
    }
}