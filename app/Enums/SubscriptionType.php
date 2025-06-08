<?php

namespace App\Enums;

enum SubscriptionType: string
{
    case FREE = 'free';
    case BASIC = 'basic';
    case PREMIUM = 'premium';
    case ENTERPRISE = 'enterprise';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function random(): self
    {
        return self::cases()[array_rand(self::cases())];
    }
}
