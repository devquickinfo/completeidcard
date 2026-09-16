<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\School;

class Mainidcard extends Model
{
     protected $table = 'mainidcards';

    protected $fillable = [
        'school_id',
        'name',
        'orientation',
        'card_width',
        'card_height',
        'background',
        'layout',
        'is_default',
        'class_id',
        'applicable_id',
        'house_id',
        'sample_id',
        
    ];

    protected $casts = [
        'layout' => 'array',
        'is_default' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
