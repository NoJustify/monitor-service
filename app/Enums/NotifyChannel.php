<?php

namespace App\Enums;

enum NotifyChannel: string
{
    case Email = 'email';
    case Telegram = 'telegram';
    case Both = 'both';
    case None = 'none';

    public function label(): string
    {
        return __('enums.notify_channel.' . $this->value);
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->label()],
            self::cases()
        );
    }
}
