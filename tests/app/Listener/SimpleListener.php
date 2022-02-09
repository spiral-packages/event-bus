<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\App\Listener;

use Spiral\EventBus\Tests\App\Event\SimpleEvent;

class SimpleListener
{
    public function handle(SimpleEvent $event): bool
    {
        return true;
    }

    public function custom(SimpleEvent $event): bool
    {
        return true;
    }
}
