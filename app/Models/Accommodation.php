<?php

namespace App\Models;

use App\Enums\AccommodationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'summary',
        'description',
        'status',
        'capacity',
        'price',
        'locationURL',
    ];

    protected $casts = [
        'status' => AccommodationStatus::class,
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function details()
    {
        return $this->belongsToMany(Detail::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
