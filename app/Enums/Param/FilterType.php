<?php

namespace App\Enums\Param;

enum FilterType:int
{
    case SELECT = 1;
    case CHECKBOX = 2;
    case INTEGER = 3;
    case COLOR = 4;

    public function cyrillic(): string
    {
        return match($this) {
            self::SELECT => 'Выпадающий список',
            self::CHECKBOX => 'Чекбокс',
            self::INTEGER => 'Число',
            self::COLOR => 'Цветовая палитра',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->map(fn($case) => [
            'value' => $case->value,
            //'label' => $case->name,
            'label_cyrillic' => $case->cyrillic(),
        ])->toArray();
    }
}
