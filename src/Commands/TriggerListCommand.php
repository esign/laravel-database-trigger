<?php

namespace Esign\DatabaseTrigger\Commands;

use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Facades\Schema;
use Illuminate\Console\Command;

class TriggerListCommand extends Command
{
    protected $signature = 'trigger:list {connection?}';
    protected $description = 'List all registered triggers';

    public function handle(): void
    {
        $connection = $this->argument('connection') ?? config('database.default', 'mysql');

        $this->table(
            ['Name', 'Event', 'Timing', 'Table', 'Statement'],
            array_map(fn (DatabaseTrigger $trigger) => [
                $trigger->name,
                $trigger->event,
                $trigger->timing,
                $trigger->table,
                $trigger->statement,
            ], Schema::connection($connection)->getTriggers())
        );
    }
}
