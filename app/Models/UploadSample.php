<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadSample extends Model
{
     protected $fillable = [
        'name',
        'file_path',
        'caption', 'orientation','school_id','applicable_id','class_id','house_id','height','width',
    ];

    public function selectedSample()
    {
        return $this->hasOne(
            SelectedSample::class,
            'sample_id', // FK in selected_samples
            'id'         // PK in upload_samples
        );
    }
}
