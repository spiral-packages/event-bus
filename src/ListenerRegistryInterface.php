<?php

declare(strict_types=1);

namespace Spiral\EventBus;

interface ListenerRegistryInterface
{
    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param int $priority The higher this value, the earlier an event
     *                      listener will be triggered in the chain (defaults to 0)
     */
    public function addListener(string $eventName, callable|string $listener, int $priority = 0): void;
}
