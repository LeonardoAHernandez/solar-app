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
        'description',
        'status',
        'capacityMin',
        'capacityMax',
        'price_lowSeason',
        'price_midSeason',
        'price_highSeason',
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
                    : '/page-resources/img/NoImage.webp';
            }
        );
    }

    /**
     * Obtiene el precio dinámico dependiendo de la temporada actual.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Si el sistema está configurado en Temporada Alta
                if (config('app.season') === 'high') {
                    return $this->price_highSeason;
                }

                // 2. Si el sistema está configurado en Temporada Media
                if (config('app.season') === 'mid') {
                    return $this->price_midSeason;
                }

                // 3. Por defecto (o si es Temporada Baja), retornamos el precio base
                return $this->price_lowSeason;
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
