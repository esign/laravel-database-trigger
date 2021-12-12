<?php

namespace Esign\DatabaseTrigger\Database\Schema\Grammars;

use Esign\DatabaseTrigger\DatabaseTrigger;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;

class MySqlGrammar extends BaseMySqlGrammar
{
    public function compileCreateTrigger(DatabaseTrigger $trigger): string
    {
        return sprintf(
            'create trigger %s %s %s on %s for each row begin %s end;',
            $trigger->name,
            $trigger->timing,
            $trigger->event,
            $trigger->table,
            $trigger->statement,
        );
    }

    public function compileDropTrigger(string $name, bool $ifExists): string
    {
        return sprintf(
            'drop trigger %s%s',
            $ifExists ? 'if exists ' : '',
            $name,
        );
    }

    public function compileTriggerExists(): string
    {
        return 'select * from information_schema.triggers where trigger_schema = ? and trigger_name = ?';
    }

    public function compileShowTriggers(): string
    {
        return 'show triggers';
    }
}
