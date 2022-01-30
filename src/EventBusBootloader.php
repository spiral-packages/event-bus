<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Core\Container;
use Spiral\Queue\QueueConnectionProviderInterface;
use Spiral\Queue\ShouldBeQueuedInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventBusBootloader extends Bootloader
{
    protected const LISTENS = [];

    protected const SINGLETONS = [
        EventDispatcherInterface::class => EventDispatcher::class,
    ];

    protected function listens(): array
    {
        return static::LISTENS;
    }

    public function booted(EventDispatcherInterface $dispatcher, Container $container): void
    {
        $events = $this->listens();

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $dispatcher->addListener(
                    $event,
                    static function (object $event, string $eventName) use ($listener, $container) {
                        $connection = is_a($listener, ShouldBeQueuedInterface::class) ? 'events' : 'sync';
                        $queue = $container->get(QueueConnectionProviderInterface::class)
                            ->getConnection($connection);

                        $queue->push(EventHandler::class, [
                            'listener' => $listener,
                            'method' => 'handle',
                            'event' => $event,
                            'eventName' => $eventName,
                        ]);
                    }
                );
            }
        }
    }
}
