<?php

namespace App\Locker\Enums;

enum LockerStatus: string
{
    case InUse = 'IN_USE';
    case Available = 'AVAILABLE';

    public function label(): string
    {
        return match($this) {
            self::InUse => 'Ocupado',
            self::Available => 'Disponible',
        };
    }
}
