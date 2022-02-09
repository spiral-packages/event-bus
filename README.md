# A simple observer pattern implementation based on symfony event handler (PSR-14 compatible)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/event-bus.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/event-bus)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/event-bus/run-tests?label=tests)](https://github.com/spiral-packages/event-bus/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/event-bus.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/event-bus)

Subscribe and listen for various events that occur within your application.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.0+
- Spiral framework 2.9+

## Installation

You can install the package via composer:

```bash
composer require spiral-packages/event-bus
```

After package install you need to register bootloader from the package.

```php
protected const LOAD = [
    // ...
    \Spiral\EventBus\Bootloader\EventBusBootloader::class,
];
```

> Note: if you are using [`spiral-packages/bootloaders-discover`](https://github.com/spiral-packages/bootloaders-discover)
> , you don't need to register bootloader by yourself anymore.

## Usage

At first need create config file app/config/event-bus.php. In this file, you can specify listeners.

```php
<?php

declare(strict_types=1);

return [
    'queueConnection' => env('EVENT_BUS_QUEUE_CONNECTION'), // default queue connection for Listeners with \Spiral\EventBus\QueueableInterface
    'discoverListeners' => env('EVENT_BUS_DISCOVER_LISTENERS', true), // Discover listeners with \Spiral\EventBus\Attribute\Listener attribute
    'listeners' => [
        UserDeleted::class => [
            DeleteUserComments::class,
        ]
    ]
];
```

#### Event example

```php
class UserDeleted 
{
    public function __construct(public string $name) {}
}
```

#### Listener example

```php
class DeleteUserComments 
{
    public function __construct(private CommentService $service) {}
    
    public function __invoke(UserDeleted $event)
    {
        $this->service->deleteCommentsForUser($event->name);
    }
}
```

#### Listener example with attributes

If you are using listeners with attributes `'discoverListeners' = true`, you don't need to register them, they will be
registered automatically.

```php
class DeleteUserComments 
{
    public function __construct(private CommentService $service) {}
    
    #[\Spiral\EventBus\Attribute\Listener]
    public function handleDeletedUser(UserDeleted $event)
    {
        $this->service->deleteCommentsForUser($event->usernname);
    }
    
    #[\Spiral\EventBus\Attribute\Listener]
    public function handleCreatedUser(UserCreated $event)
    {
        $this->service->creaateUserProfile($event->usernname);
    }
}
```

#### Listener example that should be handled in a queue

If you want to push listener to a queue, you can add `Spiral\EventBus\QueueableInterface`

```php
class DeleteUserComments implements \Spiral\EventBus\QueueableInterface
{
    // ...
}
```

#### Event dispatching

```php
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserService 
{
    public function __construct(private EventDispatcherInterface $events) {}
    
    public function deleteUserById(string $id): void
    {
        $user = User::findById($id);
        //.. 
        
        $this->events->dispatch(
            new UserDeleted($user->username)
        );
    }
}

```

## Testing



```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [butschster](https://github.com/spiral-packages)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
