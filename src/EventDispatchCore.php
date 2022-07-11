<?php

declare(strict_types=1);

namespace Spiral\EventBus;

use Psr\EventDispatcher\StoppableEventInterface;
use Spiral\Core\CoreInterface;

class EventDispatchCore implements CoreInterface
{
    /**
     * @param array{"event": object, "listeners": callable[]} $parameters
     * @return void
     */
    public function callAction(string $eventName, string $action, array $parameters = []): mixed
    {
        $event = $parameters['event'];
        $listeners = $parameters['listeners'];

        $stoppable = $event instanceof StoppableEventInterface;

        foreach ($listeners as $listener) {
            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }
            $listener($event, $eventName, $this);
        }
    }
}
