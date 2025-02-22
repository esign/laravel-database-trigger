<?php

namespace Esign\DatabaseTrigger\Commands;

use PHPUnit\Framework\Attributes\Test;
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Mockery;
use PHPUnit\Framework\MockObject\MockObject;

class MigrationCreatorTest extends TestCase
{
    #[Test]
    public function it_can_populate_the_stub_file(): void
    {
        $creator = $this->getCreator();
        $creator->expects($this->any())->method('getDatePrefix')->willReturn('foo');

        $creator
            ->getFilesystem()
            ->shouldReceive('get')
            ->once()
            ->with($creator->stubPath() . '/trigger.stub')
            ->andReturn('{{ triggerName }} {{ triggerTable }} {{ triggerEvent }} {{ triggerTiming }}');

        $creator
            ->getFilesystem()
            ->shouldReceive('put')
            ->once()
            ->with(
                'path/foo_create_my_trigger_trigger.php',
                'my_trigger posts UPDATE BEFORE'
            );

        $creator->createTrigger(
            'path',
            (new DatabaseTrigger())
                ->name('my_trigger')
                ->on('posts')
                ->timing(TriggerTiming::BEFORE)
                ->event(TriggerEvent::UPDATE)
        );
    }

    #[Test]
    public function it_can_populate_the_stub_file_providing_a_statement(): void
    {
        $creator = $this->getCreator();
        $creator->expects($this->any())->method('getDatePrefix')->willReturn('foo');

        $creator
            ->getFilesystem()
            ->shouldReceive('get')
            ->once()
            ->with($creator->stubPath() . '/trigger.stub')
            ->andReturn('{{ triggerName }} {{ triggerTable }} {{ triggerEvent }} {{ triggerTiming }} {{ triggerStatement }}');

        $creator
            ->getFilesystem()
            ->shouldReceive('put')
            ->once()
            ->with(
                'path/foo_create_my_trigger_trigger.php',
                'my_trigger posts UPDATE BEFORE OLD.id'
            );

        $creator->createTrigger(
            'path',
            (new DatabaseTrigger())
                ->name('my_trigger')
                ->on('posts')
                ->timing(TriggerTiming::BEFORE)
                ->event(TriggerEvent::UPDATE)
                ->statement('OLD.id')
        );
    }

    protected function getCreator(): MockObject
    {
        $files = Mockery::mock(FileSystem::class);

        return $this->getMockBuilder(MigrationCreator::class)
            ->onlyMethods(['getDatePrefix'])
            ->setConstructorArgs([$files])
            ->getMock();
    }
}
