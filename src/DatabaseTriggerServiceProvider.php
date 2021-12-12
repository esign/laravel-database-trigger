<?php

namespace Esign\DatabaseTrigger;

use Esign\DatabaseTrigger\Commands\TriggerListCommand;
use Esign\DatabaseTrigger\Commands\TriggerMakeCommand;
use Illuminate\Support\ServiceProvider;

class DatabaseTriggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TriggerMakeCommand::class,
                TriggerListCommand::class,
            ]);
        }
    }
}
