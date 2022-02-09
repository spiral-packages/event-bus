<?php

declare(strict_types=1);

namespace Spiral\EventBus\Testing;

use Spiral\EventBus\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait InteractsWithEvents
{
    public function fakeEventDispatcher(array $eventsToFake = []): FakeEventDispatcher
    {
        $eventDispatcher = new FakeEventDispatcher(
            $this->getDispatcher(),
            $eventsToFake
        );

        $this->getContainer()->bindSingleton(
            EventDispatcherInterface::class,
            $eventDispatcher
        );

        $this->getContainer()->bindSingleton(
            EventDispatcher::class,
            $eventDispatcher
        );

        $this->getContainer()->bindSingleton(
            \Psr\EventDispatcher\EventDispatcherInterface::class,
            $eventDispatcher
        );

        return $eventDispatcher;
    }


    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->getContainer()->get(EventDispatcherInterface::class);
    }
}
