<?php

namespace Esign\DatabaseTrigger\Tests\Commands;

use PHPUnit\Framework\Attributes\Test;
use Esign\DatabaseTrigger\Commands\TriggerListCommand;
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Facades\Schema;
use Esign\DatabaseTrigger\Tests\TestCase;

class TriggerListCommandTest extends TestCase
{
    #[Test]
    public function it_can_display_a_list_of_triggers(): void
    {
        Schema::createTrigger('my_trigger', function (DatabaseTrigger $trigger) {
            $trigger->on('posts');
            $trigger->event(TriggerEvent::UPDATE);
            $trigger->timing(TriggerTiming::BEFORE);
            $trigger->statement('');
        });

        $this
            ->artisan(TriggerListCommand::class)
            ->expectsTable(
                ['Name', 'Event', 'Timing', 'Table', 'Statement'],
                [['my_trigger', 'update', 'before', 'posts', 'begin  end']]
            )
            ->assertSuccessful();
    }
}
