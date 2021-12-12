<?php

namespace Esign\DatabaseTrigger;

use Closure;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;

class DatabaseTrigger
{
    public string $name;
    public string $table;
    public string $event;
    public string $timing;
    public string $statement;

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function on(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    public function timing(TriggerTiming $timing): self
    {
        $this->timing = $timing->value;

        return $this;
    }

    public function event(TriggerEvent $event): self
    {
        $this->event = $event->value;

        return $this;
    }

    public function statement(Closure | string $statement): self
    {
        if ($statement instanceof Closure) {
            $this->statement = $statement();
        }

        if (is_string($statement)) {
            $this->statement = $statement;
        }

        return $this;
    }
}
