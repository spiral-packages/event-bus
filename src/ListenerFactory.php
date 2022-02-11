<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Spiral\Core\Container;
use Spiral\EventBus\Config\EventBusConfig;
use Spiral\Queue\QueueConnectionProviderInterface;

final class ListenerFactory
{
    public function __construct(
        private EventBusConfig $config,
        private Container $container
    ) {
    }

    public function create(string|object $listener, string $method = 'handle'): \Closure
    {
        return function (object $event) use ($listener, $method) {
            if (\is_string($listener)) {
                $listener = $this->container->get($listener);
            }

            $this->container->invoke(
                [$listener, $method],
                ['event' => $event]
            );
        };
    }

    public function createQueueable(string|object $listener, string $method = 'handle'): \Closure
    {
        return function (object $event) use ($listener, $method) {
            $connection = is_a($listener, QueueableInterface::class, true)
                ? $this->config->getQueueConnection()
                : 'sync';

            $queue = $this->container
                ->get(QueueConnectionProviderInterface::class)
                ->getConnection($connection);

            $queue->push(EventHandler::class, [
                'listener' => $listener,
                'method' => $method,
                'event' => $event,
            ]);
        };
    }
}
