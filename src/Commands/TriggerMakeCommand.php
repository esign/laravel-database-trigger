<?php

namespace Esign\DatabaseTrigger\Commands;

use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;

class TriggerMakeCommand extends BaseCommand
{
    protected $signature = 'make:trigger {name? : The name of the trigger}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';
    protected $description = 'Create a new trigger migration';

    public function __construct(
        protected MigrationCreator $creator,
        protected Composer $composer
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $triggerTable = $this->ask('What should the trigger table be?');
        $triggerEvent = $this->choice('What should the trigger event be?', TriggerEvent::values());
        $triggerTiming = $this->choice('What should the trigger timing be?', TriggerTiming::values());
        $triggerName = $this->argument('name') ?? implode('_', [$triggerTiming, $triggerTable, $triggerEvent]);
        $trigger = (new DatabaseTrigger())
            ->name($triggerName)
            ->on($triggerTable)
            ->event(TriggerEvent::from($triggerEvent))
            ->timing(TriggerTiming::from($triggerTiming));

        $this->writeMigration($trigger);

        $this->composer->dumpAutoloads();
    }

    protected function writeMigration(DatabaseTrigger $trigger): void
    {
        $file = $this->creator->createTrigger(
            $this->getMigrationPath(),
            $trigger,
        );

        if (! $this->option('fullpath')) {
            $file = pathinfo($file, PATHINFO_FILENAME);
        }

        $this->line("<info>Created Migration:</info> {$file}");
    }
}
