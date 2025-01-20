<?php

namespace App\Room\Enums;

enum RoomStatus: string
{
    case InUse = 'IN_USE';
    case Available = 'AVAILABLE';
    case InCleaning = 'IN_CLEANING';
    case Booked = 'BOOKED';

    public function label(): string
    {
        return match($this) {
            self::InUse => 'Ocupado',
            self::Available => 'Disponible',
            self::InCleaning => 'En limpieza',
            self::Booked => 'Reservado',
        };
    }
}
