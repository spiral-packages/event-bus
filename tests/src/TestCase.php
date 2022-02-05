<?php

namespace Spiral\EventBus\Tests;

use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\EventBus\Bootloader\EventBusBootloader;

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
