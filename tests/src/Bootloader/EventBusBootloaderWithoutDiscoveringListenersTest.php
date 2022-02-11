<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\Bootloader;

use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\TestCase;

final class EventBusBootloaderWithoutDiscoveringListenersTest extends TestCase
{
    public const ENV = ['EVENT_BUS_DISCOVER_LISTENERS' => false];

    public function testSListenersShouldBeRegistered()
    {
        $this->assertCount(1, $this->getDispatcher()->getListeners(SimpleEvent::class));
        $this->assertCount(0, $this->getDispatcher()->getListeners(SimpleAnotherEvent::class));
    }
}
