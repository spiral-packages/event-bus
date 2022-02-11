<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests;

use Spiral\EventBus\EventHandler;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\App\Listener\ListenerWithAttributes;
use Spiral\EventBus\Tests\App\Listener\QueueableListener;
use Spiral\EventBus\Tests\App\Listener\SimpleListener;
use Spiral\EventBus\Tests\App\Subscriber\SimpleSubscriber;

final class EventDispatcherTest extends TestCase
{
    public function testDispatchEvent(): void
    {
        $events = $this->fakeEventDispatcher();

        $this->getDispatcher()->dispatch(new SimpleEvent());
        $this->getDispatcher()->dispatch(new SimpleAnotherEvent());

        $events->assertListening(SimpleEvent::class, SimpleListener::class);
        $events->assertListening(SimpleEvent::class, ListenerWithAttributes::class, 'methodA');
        $events->assertListening(SimpleAnotherEvent::class, ListenerWithAttributes::class, 'methodB');
    }

    public function testQueueableListenerShouldBeHandledInAQueue(): void
    {
        $queue = $this->fakeQueue();

        $this->getDispatcher()->dispatch(new SimpleEvent());

        $queue->getConnection('sync')->assertPushed(EventHandler::class, function (array $data) {
            return $data['payload']['listener'] === SimpleListener::class
                && $data['payload']['method'] === 'handle';
        });

        $queue->getConnection('sync')->assertPushed(EventHandler::class, function (array $data) {
            return $data['payload']['listener'] === ListenerWithAttributes::class
                && $data['payload']['method'] === 'methodA';
        });


        $queue->getConnection('test')->assertPushed(EventHandler::class, function (array $data) {
            return $data['payload']['listener'] === QueueableListener::class
                && $data['payload']['method'] === 'handle';
        });
    }

    public function testEventSubscriber(): void
    {
        $this->getDispatcher()->addSubscriber(new SimpleSubscriber());

        $queue = $this->fakeQueue();

        $this->getDispatcher()->dispatch(new SimpleEvent());

        $queue->getConnection('sync')->assertPushed(EventHandler::class, function (array $data) {
            return $data['payload']['listener'] instanceof SimpleSubscriber
                && $data['payload']['method'] === 'handleEvent'
                && $data['payload']['event'] instanceof SimpleEvent;
        });
    }
}
