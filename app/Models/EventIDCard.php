<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventIDCard extends Model
{
	protected $table='event_id_cards';
    protected $fillable = [
    'name',
    'image',
    'vendor_id',
   ];
}
