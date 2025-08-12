<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;
    protected $table = 'camera';
    protected $fillable = [
        'ip_camera',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    // Relasi ke DetectionImage
    public function detectionImages()
    {
        return $this->hasMany(DetectionImage::class, 'camera_id');
    }
}
