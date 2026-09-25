<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventIDCard extends Model
{
	protected $table='event_id_cards';
    protected $fillable = [
    'name',
    'file_path',
    'vendor_id',
    'height',
    'width',
    'paper_size',
    'cardperpage',
   ];
}
