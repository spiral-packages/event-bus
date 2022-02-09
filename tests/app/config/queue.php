<?php

declare(strict_types=1);

use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\AMQPCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\BeanstalkCreateInfo;
use Spiral\RoadRunner\Jobs\Queue\SQSCreateInfo;

return [
    /**
     *  Default queue connection name
     */
    'default' => env('QUEUE_CONNECTION', 'sync'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues
     */
    'aliases' => [
        // 'mail-queue' => 'roadrunner',
        // 'rating-queue' => 'sync',
    ],

    /**
     * Queue connections
     * Drivers: "sync", "roadrunner"
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
        'test' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
    ],

    'driverAliases' => [
        'sync' => \Spiral\Queue\Driver\SyncDriver::class,
    ],

    'registry' => [
        'handlers' => [],
    ],
];
