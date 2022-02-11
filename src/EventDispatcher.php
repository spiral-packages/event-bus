<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class EventDispatcher extends BaseEventDispatcher implements ListenerRegistryInterface
{
    public function __construct(
        private ListenerFactory $listenerFactory
    ) {
        parent::__construct();
    }

    public function addListener(string $eventName, callable|array|string $listener, int $priority = 0): void
    {
        if (\is_string($listener)) {
            $listener = $this->listenerFactory->createQueueable($listener);
        } elseif (\is_array($listener) && \count($listener) === 2) {
            $listener = $this->listenerFactory->createQueueable($listener[0], $listener[1]);
        }

        parent::addListener($eventName, $listener, $priority);
    }
}
