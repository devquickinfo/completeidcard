<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedSample extends Model
{
     protected $fillable = [
        'school_id',
        'sample_id',
        'orientation',
    ];


    public function uploadSample()
    {
        return $this->belongsTo(
            UploadSample::class,
            'sample_id', // FK in selected_samples
            'id'         // PK in upload_samples
        );
    }
}
