<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\Bootloader;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Spiral\EventBus\EventDispatcher;
use Spiral\EventBus\ListenerRegistryInterface;
use Spiral\EventBus\ListenersLocator;
use Spiral\EventBus\ListenersLocatorInterface;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventBusBootloaderWithDiscoveringListenersTest extends TestCase
{
    public function testEventDispatcherContainer()
    {
        $this->assertContainerBoundAsSingleton(
            EventDispatcherInterface::class,
            EventDispatcher::class
        );

        $this->assertContainerBoundAsSingleton(
            EventDispatcher::class,
            EventDispatcher::class
        );

        $this->assertContainerBoundAsSingleton(
            PsrEventDispatcherInterface::class,
            EventDispatcher::class
        );
    }

    public function testListenersLocatorContainer()
    {
        $this->assertContainerBoundAsSingleton(
            ListenersLocatorInterface::class,
            ListenersLocator::class
        );
    }

    public function testListenerRegistryContainer()
    {
        $this->assertContainerBoundAsSingleton(
            ListenerRegistryInterface::class,
            EventDispatcher::class
        );
    }

    public function testSListenersShouldBeRegistered()
    {
        $this->assertCount(3, $this->getDispatcher()->getListeners(SimpleEvent::class));
        $this->assertCount(1, $this->getDispatcher()->getListeners(SimpleAnotherEvent::class));
    }
}
