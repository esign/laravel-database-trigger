<?php

namespace Esign\DatabaseTrigger\Tests\Facades;

use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Facades\Schema;
use Esign\DatabaseTrigger\Tests\TestCase;

class SchemaTest extends TestCase
{
    /** @test */
    public function it_can_create_a_trigger()
    {
        Schema::createTrigger('my_trigger', function (DatabaseTrigger $trigger) {
            $trigger->on('posts');
            $trigger->event(TriggerEvent::INSERT);
            $trigger->timing(TriggerTiming::BEFORE);
            $trigger->statement("SET NEW.title = 'Default title';");
        });

        $this->assertTrue(Schema::hasTrigger('my_trigger'));
    }

    /** @test */
    public function it_can_drop_a_trigger()
    {
        Schema::createTrigger('my_trigger', function (DatabaseTrigger $trigger) {
            $trigger->on('posts');
            $trigger->event(TriggerEvent::INSERT);
            $trigger->timing(TriggerTiming::BEFORE);
            $trigger->statement("SET NEW.title = 'Default title';");
        });

        Schema::dropTrigger('my_trigger');

        $this->assertFalse(Schema::hasTrigger('my_trigger'));
    }

    /** @test */
    public function it_can_drop_a_trigger_if_it_exists()
    {
        Schema::dropTriggerIfExists('my_trigger');

        $this->assertFalse(Schema::hasTrigger('my_trigger'));
    }

    /** @test */
    public function it_can_check_if_a_trigger_exists()
    {
        $this->assertFalse(Schema::hasTrigger('my_trigger'));

        Schema::createTrigger('my_trigger', function (DatabaseTrigger $trigger) {
            $trigger->on('posts');
            $trigger->event(TriggerEvent::INSERT);
            $trigger->timing(TriggerTiming::BEFORE);
            $trigger->statement("SET NEW.title = 'Default title';");
        });

        $this->assertTrue(Schema::hasTrigger('my_trigger'));
    }
}
