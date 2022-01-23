<?php

namespace Esign\DatabaseTrigger;

use Esign\DatabaseTrigger\Commands\TriggerListCommand;
use Esign\DatabaseTrigger\Commands\TriggerMakeCommand;
use Esign\DatabaseTrigger\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DatabaseTriggerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('db.schema', function ($app) {
            return Schema::getSchemaBuilder(
                $app['db']->connection()
            );
        });
    }

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
