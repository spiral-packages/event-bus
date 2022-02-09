<?php

declare(strict_types=1);

namespace Spiral\EventBus;

class EventDispatcher extends \Symfony\Component\EventDispatcher\EventDispatcher
{
    public function __construct(
        private ListenerFactory $listenerFactory
    ) {
        parent::__construct();
    }

    public function addListener(string $eventName, callable|array|string $listener, int $priority = 0): void
    {
        if (\is_string($listener)) {
            $listener = $this->listenerFactory->create($listener);
        }

        parent::addListener($eventName, $listener, $priority);
    }
}
