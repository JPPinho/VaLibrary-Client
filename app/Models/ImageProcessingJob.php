<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageProcessingJob extends Model
{
    protected $guarded = [];

    protected $casts = [
        'file_paths' => 'array',
    ];
}
