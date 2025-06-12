<?php

namespace App\Enums;

enum EmployeeRole: string
{
    case DEVELOPER = 'developer';
    case DESIGNER = 'designer';
    case PROJECT_MANAGER = 'project manager';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
