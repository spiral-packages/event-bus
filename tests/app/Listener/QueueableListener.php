<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\App\Listener;

use Spiral\EventBus\Attribute\Listener;
use Spiral\EventBus\QueueableInterface;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;

class QueueableListener implements QueueableInterface
{
    #[Listener]
    public function handle(SimpleEvent $event)
    {

    }
}
