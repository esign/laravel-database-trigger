<?php

namespace Esign\DatabaseTrigger\Tests;

use PHPUnit\Framework\Attributes\Test;
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;

class DatabaseTriggerTest extends TestCase
{
    #[Test]
    public function it_can_set_the_name()
    {
        $trigger = (new DatabaseTrigger())->name('my_trigger');

        $this->assertEquals('my_trigger', $trigger->name);
    }

    #[Test]
    public function it_can_set_the_table_name()
    {
        $trigger = (new DatabaseTrigger())->on('posts');

        $this->assertEquals('posts', $trigger->table);
    }

    #[Test]
    public function it_can_set_the_timing()
    {
        $trigger = (new DatabaseTrigger())->timing(TriggerTiming::AFTER);

        $this->assertEquals(TriggerTiming::AFTER->value, $trigger->timing);
    }

    #[Test]
    public function it_can_set_the_event()
    {
        $trigger = (new DatabaseTrigger())->event(TriggerEvent::INSERT);

        $this->assertEquals(TriggerEvent::INSERT->value, $trigger->event);
    }

    #[Test]
    public function it_can_set_the_statement_as_a_closure()
    {
        $trigger = (new DatabaseTrigger())->statement(function () {
            return "SET NEW.title = 'Default title';";
        });

        $this->assertEquals("SET NEW.title = 'Default title';", $trigger->statement);
    }

    #[Test]
    public function it_can_set_the_statement_as_a_string()
    {
        $trigger = (new DatabaseTrigger())->statement("SET NEW.title = 'Default title';");

        $this->assertEquals("SET NEW.title = 'Default title';", $trigger->statement);
    }
}
