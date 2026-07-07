<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_id',
    ];

    public function accommodations()
    {
        return $this->belongsToMany(Accommodation::class);
    }

    // Un servicio pertenece a un icono
    public function icon(): BelongsTo
    {
        return $this->belongsTo(Icon::class);
    }
}
