<?php

namespace Esign\DatabaseTrigger\Database\Schema;

use Closure;
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Illuminate\Database\Schema\MySqlBuilder as BaseMySqlBuilder;

class MySqlBuilder extends BaseMySqlBuilder
{
    public function createTrigger(string $name, Closure $callback): void
    {
        $trigger = tap(
            (new DatabaseTrigger())->name($name),
            fn ($trigger) => $callback($trigger)
        );
        $this->connection->unprepared(
            $this->grammar->compileCreateTrigger($trigger)
        );
    }

    public function dropTrigger(string $name, bool $ifExists = false): void
    {
        $this->connection->unprepared(
            $this->grammar->compileDropTrigger($name, $ifExists)
        );
    }

    public function dropTriggerIfExists(string $name): void
    {
        $this->dropTrigger($name, true);
    }

    public function hasTrigger(string $name): bool
    {
        $trigger = $this->connection->selectOne(
            $this->grammar->compileTriggerExists(),
            $this->getBindingsForHasTrigger($name)
        );

        return ! is_null($trigger);
    }

    protected function getBindingsForHasTrigger(string $name): array
    {
        return [$this->connection->getDatabaseName(), $name];
    }

    public function getTriggers(): array
    {
        $triggers = $this->connection->select(
            $this->grammar->compileShowTriggers()
        );

        return array_map(function (object $trigger) {
            return (new DatabaseTrigger())
                ->name($trigger->Trigger)
                ->on($trigger->Table)
                ->event(TriggerEvent::from(strtolower($trigger->Event)))
                ->timing(TriggerTiming::from(strtolower($trigger->Timing)))
                ->statement($trigger->Statement);
        }, $triggers);
    }
}
