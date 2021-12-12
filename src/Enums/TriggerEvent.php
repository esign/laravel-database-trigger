<?php

namespace Esign\DatabaseTrigger\Enums;

use Esign\DatabaseTrigger\Concerns\HasEnumValues;

enum TriggerEvent: string
{
    use HasEnumValues;

    case INSERT = 'insert';
    case UPDATE = 'update';
    case DELETE = 'delete';
}
