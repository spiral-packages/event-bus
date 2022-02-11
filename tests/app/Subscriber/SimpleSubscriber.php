<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\App\Subscriber;

use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SimpleSubscriber implements EventSubscriberInterface
{
    public function handleEvent(SimpleEvent $event): void
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            SimpleEvent::class => 'handleEvent',
        ];
    }
}
