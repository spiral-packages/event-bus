<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\App\Listener;

use Spiral\EventBus\Attribute\Listener;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;

class ListenerWithAttributes
{
    #[Listener]
    public function methodA(SimpleEvent $event): bool
    {
        return true;
    }

    #[Listener]
    public function methodB(SimpleAnotherEvent $event): bool
    {
        return true;
    }
}
