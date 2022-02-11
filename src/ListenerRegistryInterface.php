<?php

declare(strict_types=1);

namespace Spiral\EventBus;

interface ListenerRegistryInterface
{
    /**
     * @param string $event
     * @param callable|string $listener
     * @param int $priority
     * @return void
     */
    public function addListener(string $event, callable|string $listener, int $priority = 0): void;
}
