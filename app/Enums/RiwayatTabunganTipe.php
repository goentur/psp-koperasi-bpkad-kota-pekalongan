<?php

namespace App\Enums;

enum RiwayatTabunganTipe: string
{
    case MASUK = 'MASUK';
    case KELUAR = 'KELUAR';

    public function label(): string
    {
        return match ($this) {
            self::MASUK => 'MASUK',
            self::KELUAR => 'KELUAR',
        };
    }
}
