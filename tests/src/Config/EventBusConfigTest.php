<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\Config;

use Spiral\EventBus\Config\EventBusConfig;
use Spiral\EventBus\Tests\TestCase;

final class EventBusConfigTest extends TestCase
{
    public function testGetsInterceptorsWithNullValue()
    {
        $config = new EventBusConfig(['interceptors' => null]);

        $this->assertSame([], $config->getInterceptors());
    }

    public function testGetsInterceptorsWithoutValue()
    {
        $config = new EventBusConfig([]);

        $this->assertSame([], $config->getInterceptors());
    }

    public function testGetsInterceptorsWithValue()
    {
        $config = new EventBusConfig(['interceptors' => ['foo']]);

        $this->assertSame(['foo'], $config->getInterceptors());
    }
}
