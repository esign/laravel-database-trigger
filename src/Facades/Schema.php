<?php

namespace Esign\DatabaseTrigger\Facades;

use Esign\DatabaseTrigger\Database\Schema\Grammars\MySqlGrammar;
use Esign\DatabaseTrigger\Database\Schema\MySqlBuilder;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema as BaseSchema;
use RuntimeException;

class Schema extends BaseSchema
{
    public static function connection($name): Builder
    {
        return static::getSchemaBuilder(
            static::$app['db']->connection($name)
        );
    }

    public static function getSchemaBuilder(Connection $connection): Builder
    {
        $getSchemaBuilderForGrammar = function (string $grammarClass, string $builderClass) use ($connection): Builder {
            $connection->setSchemaGrammar(new $grammarClass($connection));

            return new $builderClass($connection);
        };

        return match ($driver = $connection->getDriverName()) {
            'mysql' => $getSchemaBuilderForGrammar(MySqlGrammar::class, MySqlBuilder::class),
            default => throw new RuntimeException("Database driver [$driver] not supported."),
        };
    }
}
