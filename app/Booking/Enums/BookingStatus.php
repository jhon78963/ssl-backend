<?php

namespace App\Booking\Enums;

enum BookingStatus: string
{
    case Pending = 'PENDING';
    case InUse = 'IN_USE';
    case Cancelled = 'CENCELLED';
    case Completed = 'COMPLETED';

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Pendiente',
            self::InUse => 'En uso',
            self::Cancelled => 'Cancelado',
            self::Completed => 'Finalizado',
        };
    }
}
