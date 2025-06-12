<?php

namespace App\Support;

class KodeRefresnsiRiwayatSaldo
{
    public static ?string $source = null;

    public static function set(string $from): void
    {
        self::$source = $from;
    }

    public static function get(): ?string
    {
        return self::$source;
    }

    public static function clear(): void
    {
        self::$source = null;
    }
}
