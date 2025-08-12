<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetectionImage extends Model
{
    use HasFactory;
    protected $table = 'detection_image';
    protected $fillable = [
        'detection_image',
        'camera_id',
    ];

    public function camera()
    {
        return $this->belongsTo(Camera::class, 'camera_id');
    }
}
