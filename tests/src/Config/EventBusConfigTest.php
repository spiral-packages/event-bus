<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\Config;

use Spiral\EventBus\Config\EventBusConfig;
use Spiral\EventBus\Tests\TestCase;

final class EventBusConfigTest extends TestCase
{
    public function testGetsListenersIfKeyNotExists()
    {
        $config = new EventBusConfig([]);

        $this->assertSame([], $config->getListeners());
    }

    public function testGetsListeners()
    {
        $config = new EventBusConfig(['listeners' => ['foo']]);

        $this->assertSame(['foo'], $config->getListeners());
    }

    public function testGetsQueueConnection()
    {
        $config = new EventBusConfig(['queueConnection' => 'test']);

        $this->assertSame('test', $config->getQueueConnection());
    }

    public function testGetsQueueConnectionWithNullValue()
    {
        $config = new EventBusConfig(['queueConnection' => null]);

        $this->assertNull($config->getQueueConnection());
    }

    public function testGetsQueueConnectionIfKeyNotExists()
    {
        $config = new EventBusConfig([]);

        $this->assertNull($config->getQueueConnection());
    }

    public function testGetsDiscoverListeners()
    {
        $config = new EventBusConfig(['discoverListeners' => false]);

        $this->assertFalse($config->discoverListeners());
    }

    public function testGetsDiscoverListenersIfKeyNotExists()
    {
        $config = new EventBusConfig([]);

        $this->assertTrue($config->discoverListeners());
    }
}
