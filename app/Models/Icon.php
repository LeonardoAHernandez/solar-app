<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    protected $fillable = ['name', 'class_name'];

    // Un icono puede ser usado por muchos servicios
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
