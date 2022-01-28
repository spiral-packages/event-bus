<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Spiral\Core\Container;
use Spiral\Queue\HandlerInterface;

final class EventHandler implements HandlerInterface
{
    public function __construct(private Container $container)
    {
    }

    public function handle(string $name, string $id, array $payload): void
    {
        $event = $payload['event'];
        $method = $payload['method'] ?? 'handle';

        $listener = $this->container->get($payload['listener']);

        $handler = new \ReflectionClass($listener);
        $method = $handler->getMethod(
            $handler->hasMethod($method) ? $method : '__invoke'
        );

        $args = $this->container->resolveArguments($method, [
            'event' => $event,
        ]);

        $method->invokeArgs($listener, $args);
    }
}
