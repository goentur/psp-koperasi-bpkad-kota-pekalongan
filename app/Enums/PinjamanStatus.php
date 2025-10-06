<?php

namespace App\Enums;

enum PinjamanStatus: string
{
    case DISETUJUI = 'DISETUJUI';

    public function label(): string
    {
        return match ($this) {
            self::DISETUJUI => 'DISETUJUI',
        };
    }
}
