<?php

namespace Esign\DatabaseTrigger\Tests;

use Esign\DatabaseTrigger\DatabaseTriggerServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('posts');

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [DatabaseTriggerServiceProvider::class];
    }
}
