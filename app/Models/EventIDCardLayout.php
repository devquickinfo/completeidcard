<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventIDCardLayout extends Model
{
    protected $fillable = [
    'vendor_id',
    'event_id',
    'name',
    'height',
    'width',
    'layout',
    'background',
    'is_default',
    'sample_id',
    'event_id',
   ];
    protected $casts = [
    'layout'     => 'array',
    'is_default' => 'boolean',
];

}
