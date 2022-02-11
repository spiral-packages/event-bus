<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests;

use Spiral\EventBus\ListenersLocatorInterface;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes;
use Spiral\EventBus\Tests\App\Listener\QueueableListener;

final class ListenersLocatorTest extends TestCase
{
    public function testListenersWithAttributesShouldBeFound(): void
    {
        $locator = $this->getContainer()->get(ListenersLocatorInterface::class);

        $this->assertSame([
            SimpleEvent::class => [
                [
                    ListenerWithAttributes::class,
                    'methodA'
                ],
                [
                    QueueableListener::class,
                    'handle'
                ]
            ],
            SimpleAnotherEvent::class => [
                [
                    ListenerWithAttributes::class,
                    'methodB'
                ],
            ]
        ], $locator->getListeners());
    }
}
