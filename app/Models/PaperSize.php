<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperSize extends Model
{
    protected $table='paper_size';
    protected $fillable = [
        'size',
        'height',
        'width',
    ];
}
