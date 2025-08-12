<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'location';
    protected $fillable = [
        'name_location',
        'location_image',
    ];

    public function images()
    {
        return $this->hasMany(OriginalImageDetection::class, 'location_id');
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class, 'location_id');
    }
}
