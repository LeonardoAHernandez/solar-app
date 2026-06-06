<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'type',
        'position',
        'accommodation_id',
    ];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    // protected function image_for_src():Attribute{
    //     return new Attribute(
    //         get: function(){
    //             return $this->image_path ? Storage::url($this->image_path) : 'https://image.pngaaa.com/13/1887013-middle.png';
    //         }
    //     );
    // }
    // Cambia el nombre del método a camelCase
    protected function imageForSrc(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image_path
                ? Storage::url($this->image_path)
                : 'https://image.pngaaa.com/13/1887013-middle.png'
        );
    }
}
