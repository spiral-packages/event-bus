<?php

namespace Spiral\EventBus\Tests;

use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\EventBus\Bootloader\EventBusBootloader;
use Spiral\EventBus\Testing\InteractsWithEvents;
use Spiral\Queue\Bootloader\QueueBootloader;

abstract class TestCase extends \Spiral\Testing\TestCase
{
    public function rootDirectory(): string
    {
        return __DIR__.'/../';
    }

    public function defineBootloaders(): array
    {
        return [
            ConfigurationBootloader::class,
            EventBusBootloader::class,
        ];
    }
}
