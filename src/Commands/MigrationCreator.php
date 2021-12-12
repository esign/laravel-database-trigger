<?php

namespace Esign\DatabaseTrigger\Commands;

use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Illuminate\Database\Migrations\MigrationCreator as BaseMigrationCreator;
use Illuminate\Filesystem\Filesystem;

class MigrationCreator extends BaseMigrationCreator
{
    public function __construct(Filesystem $files)
    {
        parent::__construct($files, '');
    }

    public function createTrigger(string $path, DatabaseTrigger $trigger): string
    {
        $stub = $this->getTriggerStub();

        $this->files->put(
            $path = $this->getTriggerPath($trigger->name, $path),
            $this->populateTriggerStub($stub, $trigger)
        );

        return $path;
    }

    protected function populateTriggerStub(string $stub, DatabaseTrigger $trigger): string
    {
        $replacements = [
            '{{ triggerName }}' => $trigger->name,
            '{{ triggerTable }}' => $trigger->table,
            '{{ triggerEvent }}' => TriggerEvent::from($trigger->event)->name,
            '{{ triggerTiming }}' => TriggerTiming::from($trigger->timing)->name,
        ];

        foreach ($replacements as $search => $replacement) {
            $stub = str_replace($search, $replacement, $stub);
        }

        return $stub;
    }

    protected function getTriggerPath(string $triggerName, string $path): string
    {
        return sprintf(
            '%s/%s_create_%s_trigger.php',
            $path,
            $this->getDatePrefix(),
            $triggerName
        );
    }

    protected function getTriggerStub(): string
    {
        return $this->files->get(
            $this->stubPath() . '/trigger.stub'
        );
    }

    public function stubPath(): string
    {
        return __DIR__ . '/stubs';
    }
}
