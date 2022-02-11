<?php

declare(strict_types=1);

use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\App\Listener\SimpleListener;

return [
    'queueConnection' => 'test',
    'discoverListeners' => env('EVENT_BUS_DISCOVER_LISTENERS', true),
    'listeners' => [
        SimpleEvent::class => [
            SimpleListener::class,
        ],
    ],
];
