<?php

namespace Esign\DatabaseTrigger\Enums;

use Esign\DatabaseTrigger\Concerns\HasEnumValues;

enum TriggerTiming: string
{
    use HasEnumValues;

    case AFTER = 'after';
    case BEFORE = 'before';
}
