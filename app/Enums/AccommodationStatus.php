<?php

namespace App\Enums;

enum AccommodationStatus: int
{
    case BORRADOR = 1;
    case ACTIVO = 2;
    case INACTIVO = 3;    

    public function label(): string
    {
        return match($this) {
            self::BORRADOR => 'Borrador',
            self::ACTIVO => 'Activo',
            self::INACTIVO => 'Inactivo',
        };
    }
}
