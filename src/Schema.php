<?php

namespace Esign\DatabaseTrigger;

use Esign\DatabaseTrigger\Database\Schema\Grammars\MySqlGrammar;
use Esign\DatabaseTrigger\Database\Schema\MySqlBuilder;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

class Schema extends Facade
{
    protected static function getFacadeAccessor(): Builder
    {
        return static::getSchemaBuilder(
            static::$app['db']->connection()
        );
    }

    public static function connection(string $name): Builder
    {
        return static::getSchemaBuilder(
            static::$app['db']->connection($name)
        );
    }

    public static function getSchemaBuilder(Connection $connection): Builder
    {
        $getSchemaBuilderForGrammar = function (string $grammarClass, string $builderClass) use ($connection): Builder {
            $connection->setSchemaGrammar($connection->withTablePrefix(new $grammarClass()));

            return new $builderClass($connection);
        };

        return match ($driver = $connection->getDriverName()) {
            'mysql' => $getSchemaBuilderForGrammar(MySqlGrammar::class, MySqlBuilder::class),
            default => throw new RuntimeException("Database driver [$driver] not supported."),
        };
    }
}
