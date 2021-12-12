# Create database triggers from migrations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-database-trigger.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-database-trigger)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-database-trigger.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-database-trigger)
![GitHub Actions](https://github.com/esign/laravel-database-trigger/actions/workflows/main.yml/badge.svg)

This package provides an easy way to manage database triggers within your Laravel application. Currently only [MySQL](https://dev.mysql.com/doc/refman/8.0/en/trigger-syntax.html) is supported.

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-database-trigger
```

## Usage

### Creating triggers
To create a new database trigger, use the `createTrigger` method on the `Schema` facade.
The `createTrigger` method accepts two arguments: the first is the name of the trigger, while the second is a closure which receives a `DatabaseTrigger` object that may be used to define the new trigger:

```php
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Schema;

Schema::createTrigger('my_trigger', function (DatabaseTrigger $trigger) {
    $trigger->on('posts');
    $trigger->event(TriggerEvent::INSERT);
    $trigger->timing(TriggerTiming::BEFORE);
    $trigger->statement("SET NEW.title = 'Default title';");
});
```

### Checking for trigger existence
You may check for the existence of a trigger by using the `hasTrigger` method:

```php
use Esign\DatabaseTrigger\Schema;

Schema::hasTrigger('my_trigger');
```

### Dropping triggers
You may drop an existing trigger by using the `dropTrigger` or `dropTriggerIfExists` methods:

```php
use Esign\DatabaseTrigger\Schema;

Schema::dropTrigger('my_trigger');

Schema::dropTriggerIfExists('my_trigger');
```

### Generating migrations
You may use the `make:trigger` artisan command to quickly generate a trigger migration:

```bash
php artisan make:trigger
```

This will publish a migration with the following contents:

```php
use Esign\DatabaseTrigger\DatabaseTrigger;
use Esign\DatabaseTrigger\Enums\TriggerEvent;
use Esign\DatabaseTrigger\Enums\TriggerTiming;
use Esign\DatabaseTrigger\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createTrigger('before_posts_update', function (DatabaseTrigger $trigger) {
            $trigger->on('posts');
            $trigger->timing(TriggerTiming::BEFORE);
            $trigger->event(TriggerEvent::UPDATE);
            $trigger->statement('');
        });
    }

    public function down(): void
    {
        Schema::dropTriggerIfExists('before_posts_update');
    }
};
```

A name for the trigger will automatically be created based on the provided input.
In case you want to use a diffferent trigger name, you may pass it as the first argument:

```bash
php artisan make:trigger my_trigger
```

### Displaying existing triggers
To display a list of your existing triggers you may use the `trigger:list` command:

```bash
php artisan trigger:list

php artisan trigger:list <connection>
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
