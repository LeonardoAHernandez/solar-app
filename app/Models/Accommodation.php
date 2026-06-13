<?php

namespace App\Models;

use App\Enums\AccommodationStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'summary',
        'description',
        'status',
        'capacity',
        'price',
        'price_highseason',
        'locationURL',
        'published_at',
    ];

    protected $casts = [
        'status' => AccommodationStatus::class,
        'published_at' => 'datetime',
    ];

    protected function image(): Attribute
    {
        return new Attribute(
            get: function () {
                // 1. Buscamos primero la imagen configurada como portada/principal
                $principalImage = $this->images()->where('type', 'principal')->first();

                // 2. Si no hay principal, usamos la primera de la galería como respaldo
                $fallbackImage = $principalImage ?? $this->images()->first();

                // 3. Retornamos la URL correspondiente o la imagen por defecto
                return $fallbackImage
                    ? Storage::url($fallbackImage->image_path)
                    : 'https://image.pngaaa.com/13/1887013-middle.png';
            }
        );
    }

    /**
     * Obtiene el precio dinámico dependiendo de la temporada actual.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value) {
                // Evaluamos la configuración global que definimos en el ServiceProvider
                if (config('app.is_high_season', false)) {
                    // Si es temporada alta, retornamos el precio especial (o el normal si el especial está vacío)
                    return $this->price_highseason ?? $value;
                }

                // Si es temporada baja, retornamos el valor original de la columna 'price'
                return $value;
            }
        );
    }


    // Relaciones
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
        return $this->belongsToMany(Detail::class)->withPivot('quantity');
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
