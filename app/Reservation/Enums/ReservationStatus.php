<?php

namespace App\Reservation\Enums;

enum ReservationStatus: string
{
    case InUse = 'IN_USE';
    case Cancelled = 'CENCELLED';
    case Completed = 'COMPLETED';

    public function label(): string
    {
        return match($this) {
            self::InUse => 'En uso',
            self::Cancelled => 'Cancelado',
            self::Completed => 'Finalizado',
        };
    }
}
