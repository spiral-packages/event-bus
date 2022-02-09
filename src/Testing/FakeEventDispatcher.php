<?php

declare(strict_types=1);

namespace Spiral\EventBus\Testing;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class FakeEventDispatcher implements EventDispatcherInterface
{
    private array $eventsToFake;
    private array $events = [];

    public function __construct(
        private EventDispatcherInterface $dispatcher,
        array|string $eventsToFake = []
    ) {
        $this->eventsToFake = (array)$eventsToFake;
    }

    public function assertListening(string $event, string $listener, string $method = 'handle'): void
    {
        foreach ($this->dispatcher->getListeners($event) as $listenerClosure) {
            $actualListenerVariables = (new \ReflectionFunction($listenerClosure))
                ->getStaticVariables();

            if ($actualListenerVariables['listener'] === $listener && $actualListenerVariables['method'] === $method) {
                TestCase::assertTrue(true);

                return;
            }
        }

        TestCase::assertTrue(
            false,
            \sprintf(
                'Event [%s] does not have the [%s::%s] listener attached to it',
                $event,
                $listener,
                $method
            )
        );
    }

    public function assertDispatched($event, \Closure $callback = null): void
    {
        TestCase::assertTrue(
            \count($this->getDispatchedEvents($event, $callback)) > 0,
            \sprintf('The expected [%s] event was not dispatched.', $event)
        );
    }

    public function assertNotDispatched($event, \Closure $callback = null): void
    {
        TestCase::assertCount(
            0,
            $this->getDispatchedEvents($event, $callback),
            \sprintf('The expected [%s] event was dispatched.', $event)
        );
    }

    public function assertDispatchedTimes($event, int $times = 1): void
    {
        $count = $this->getDispatchedEvents($event);

        TestCase::assertCount(
            $times,
            $count,
            \sprintf('The expected [%s] event was dispatched %d times instead of %d times.', $event, $count, $times)
        );
    }

    public function assertNothingDispatched(): void
    {
        $count = \count($this->events);

        TestCase::assertSame(
            0,
            $count,
            \sprintf('%d unexpected events were dispatched.', $count)
        );
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0)
    {
        return $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        return $this->dispatcher->addSubscriber($subscriber);
    }

    public function removeListener(string $eventName, callable $listener)
    {
        return $this->dispatcher->removeListener($eventName, $listener);
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        return $this->dispatcher->removeSubscriber($subscriber);
    }

    public function getListeners(string $eventName = null): array
    {
        return $this->dispatcher->getListeners($eventName);
    }

    public function dispatch(object $event, string $eventName = null): object
    {
        if ($this->shouldBeFakedEvent($event::class)) {
            $this->events[$event::class][] = func_get_args();

            return $event;
        }

        return $this->dispatcher->dispatch($event, $eventName);
    }

    public function getListenerPriority(string $eventName, callable $listener): ?int
    {
        return $this->dispatcher->getListenerPriority($eventName, $listener);
    }

    public function hasListeners(string $eventName = null): bool
    {
        return $this->dispatcher->hasListeners($eventName);
    }

    private function shouldBeFakedEvent(string $event): bool
    {
        if ($this->eventsToFake === []) {
            return true;
        }

        return \in_array($event, $this->eventsToFake);
    }

    private function getDispatchedEvents(string $event, \Closure $callback = null): array
    {
        if (! $this->hasDispatched($event)) {
            return [];
        }

        $callback = $callback ?: static function () {
            return true;
        };

        $events = [];

        foreach ($this->events[$event] as $arguments) {
            if ($callback(...$arguments)) {
                $events[] = $event;
            }
        }

        return $events;
    }

    private function hasDispatched(string $event): bool
    {
        return isset($this->events[$event]) && $this->events[$event] !== [];
    }
}
