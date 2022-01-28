# This is my package event-bus

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spiral-packages/event-bus.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/event-bus)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spiral-packages/event-bus/run-tests?label=tests)](https://github.com/spiral-packages/event-bus/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spiral-packages/event-bus.svg?style=flat-square)](https://packagist.org/packages/spiral-packages/event-bus)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.0+
- Spiral framework 2.9+

## Installation

You can install the package via composer:

```bash
composer require spiral-packages/event-bus
```

Add a new alias in `queue` config and associate it with desired queue connection:

```php
'aliases' => [
    'events' => 'sync',
],
```

## Usage

Create a new bootloader, for example, `EventsBootloader`

```php
use Psr\Log\LoggerInterface;
use Spiral\EventBus\EventBusBootloader;

final class EventsBootloader extends EventBusBootloader
{
    protected const LISTENS = [
        UserDeleted::class => [
            DeleteUserComments::class,
        ]
    ];
}
```

And register it in your application

```php
protected const LOAD = [
    // ...
    EventsBootloader::class,
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
