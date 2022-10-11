<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Spiral\Core\CoreInterface;
use Spiral\Events\ListenerRegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class EventDispatcher extends BaseEventDispatcher implements ListenerRegistryInterface
{
    public function __construct(
        private readonly CoreInterface $core
    ) {
        parent::__construct();
    }

    /**{@inheritDoc}*/
    protected function callListeners(iterable $listeners, string $eventName, object $event): void
    {
        $this->core->callAction($eventName, 'dispatch', [
            'event' => $event,
            'listeners' => $listeners,
        ]);
    }

    public function addListener(string $event, callable|array $listener, int $priority = 0): void
    {
        parent::addListener($event, $listener, $priority);
    }
}
