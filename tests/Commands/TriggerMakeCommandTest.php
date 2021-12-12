<?php

namespace Esign\DatabaseTrigger\Tests\Commands;

use Esign\DatabaseTrigger\Commands\MigrationCreator;
use Esign\DatabaseTrigger\Commands\TriggerMakeCommand;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Tests\TestCase;
use Illuminate\Support\Composer;

class TriggerMakeCommandTest extends TestCase
{
    /** @test */
    public function it_can_create_the_trigger_migration()
    {
        $this->mock(MigrationCreator::class, function ($mock) {
            $mock->shouldReceive('createTrigger')->once();
        });

        $this->mock(Composer::class, function ($mock) {
            $mock->shouldReceive('dumpAutoloads')->once();
        });

        $this
            ->artisan(TriggerMakeCommand::class, ['my_trigger'])
            ->expectsQuestion('What should the trigger table be?', 'posts')
            ->expectsChoice('What should the trigger event be?', TriggerEvent::UPDATE->value, TriggerEvent::values())
            ->expectsChoice('What should the trigger timing be?', TriggerTiming::BEFORE->value, TriggerTiming::values())
            ->assertSuccessful();
    }
}
