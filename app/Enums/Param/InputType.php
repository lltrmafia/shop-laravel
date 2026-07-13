<?php

namespace App\Enums\Param;

enum InputType:int
{
    case SELECT = 1;
    case CHECKBOX = 2;
    case INTEGER = 3;
    case COLOR = 4;

    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'title' => mb_strtolower($case->name),
            'value' => $case->value,
        ])->toArray();
    }
}
