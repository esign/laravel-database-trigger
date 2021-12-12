<?php

namespace Esign\DatabaseTrigger\Concerns;

trait HasEnumValues
{
    public static function values(): array
    {
        return array_map(
            fn (self $case) => $case->value,
            self::cases(),
        );
    }
}
