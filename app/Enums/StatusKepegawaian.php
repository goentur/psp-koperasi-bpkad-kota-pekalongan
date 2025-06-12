<?php

namespace App\Enums;

enum StatusKepegawaian: string
{
    case ASN = 'ASN';
    case NON_ASN = 'NON ASN';

    public function label(): string
    {
        return match ($this) {
            self::ASN => 'ASN',
            self::NON_ASN => 'NON ASN',
        };
    }
}
