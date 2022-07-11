<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests;

use Psr\EventDispatcher\StoppableEventInterface;
use Spiral\EventBus\EventDispatchCore;

final class EventDispatchCoreTest extends TestCase
{
    public function testCallAction(): void
    {
        $event = new \stdClass();
        $total = 0;

        $core = new EventDispatchCore();

        $core->callAction('foo', 'dispatch', [
            'event' => new \stdClass(),
            'listeners' => [
                function ($e) use ($event, &$run, &$total) {
                    $total++;
                    $this->assertEquals($event, $e);
                },
                function ($e) use (&$total) {
                    $total++;
                },
            ],
        ]);

        $this->assertEquals(2, $total);
    }

    public function testCallActionWithPropagation(): void
    {
        $event = new class implements StoppableEventInterface {
            private bool $stop = false;
            public function isPropagationStopped(): bool
            {
                return $this->stop;
            }

            public function stop(): void
            {
                $this->stop = true;
            }
        };

        $total = 0;

        $core = new EventDispatchCore();

        $core->callAction('foo', 'dispatch', [
            'event' => $event,
            'listeners' => [
                function ($e) use ($event, &$run, &$total) {
                    $total++;
                    $e->stop();
                    $this->assertEquals($event, $e);
                },
                function ($e) use (&$total) {
                    $total++;
                },
            ],
        ]);

        $this->assertEquals(1, $total);
    }
}
