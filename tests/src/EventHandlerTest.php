<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests;

use Spiral\Core\Exception\Container\ContainerException;
use Spiral\EventBus\EventHandler;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\App\Listener\SimpleListener;

final class EventHandlerTest extends TestCase
{
    public function testHandleEventWithDefaultMethod()
    {
        $event = new SimpleEvent();

        $listener = $this->mockContainer(SimpleListener::class);
        $listener->shouldReceive('handle')->once();

        $handler = new EventHandler($this->getContainer());

        $handler->handle('foo', 'bar', [
            'event' => $event,
            'listener' => SimpleListener::class,
        ]);
    }

    public function testHandleEventWithMethodFromPayload()
    {
        $event = new SimpleEvent();

        $listener = $this->mockContainer(SimpleListener::class);
        $listener->shouldReceive('custom')->once();

        $handler = new EventHandler($this->getContainer());

        $handler->handle('foo', 'bar', [
            'event' => $event,
            'method' => 'custom',
            'listener' => SimpleListener::class,
        ]);
    }

    public function testHandleEventWithNonExistMethodFromPayload()
    {
        $this->expectException(ContainerException::class);
        $this->expectErrorMessage(
            'Method Spiral\EventBus\Tests\App\Listener\SimpleListener::__invoke() does not exist'
        );

        $handler = new EventHandler($this->getContainer());

        $handler->handle('foo', 'bar', [
            'event' => new SimpleEvent(),
            'method' => 'unknown',
            'listener' => SimpleListener::class,
        ]);
    }
}
