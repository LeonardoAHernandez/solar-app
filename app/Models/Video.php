<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_path',
        'type',
        'position',
        'accommodation_id',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }
}
