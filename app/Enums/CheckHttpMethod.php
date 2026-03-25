<?php

namespace App\Enums;

enum CheckHttpMethod: string
{
    case Get = 'GET';
    case Head = 'HEAD';

    public function label(): string
    {
        return __('enums.http_method.' . $this->value);
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
