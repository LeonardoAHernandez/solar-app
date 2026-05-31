<?php

namespace App\Enums;

enum AccommodationStatus: int
{
    case BORRADOR = 1;
    case ACTIVO = 2;
    case INACTIVO = 3;    
}
