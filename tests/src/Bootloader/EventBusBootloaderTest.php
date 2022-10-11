<?php

declare(strict_types=1);

namespace Spiral\EventBus\Tests\Bootloader;

use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Spiral\Config\ConfigManager;
use Spiral\Config\LoaderInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\EventBus\Bootloader\EventBusBootloader;
use Spiral\EventBus\Config\EventBusConfig;
use Spiral\EventBus\EventDispatcher;
use Spiral\EventBus\ListenerRegistryInterface;
use Spiral\EventBus\ListenersLocator;
use Spiral\EventBus\ListenersLocatorInterface;
use Spiral\EventBus\Tests\App\Event\SimpleAnotherEvent;
use Spiral\EventBus\Tests\App\Event\SimpleEvent;
use Spiral\EventBus\Tests\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventBusBootloaderTest extends TestCase
{
    public function testEventDispatcherContainer()
    {
        $this->assertContainerBoundAsSingleton(
            EventDispatcherInterface::class,
            EventDispatcher::class
        );

        $this->assertContainerBoundAsSingleton(
            EventDispatcher::class,
            EventDispatcher::class
        );

        $this->assertContainerBoundAsSingleton(
            PsrEventDispatcherInterface::class,
            EventDispatcher::class
        );
    }

    public function testRegisterInterceptor(): void
    {
        $configs = new ConfigManager($this->createMock(LoaderInterface::class));
        $configs->setDefaults(EventBusConfig::CONFIG, ['interceptors' => []]);

        $bootloader = new EventBusBootloader($configs);

        $bootloader->addInterceptor('foo');
        $this->assertSame(
            ['foo'],
            $configs->getConfig(EventBusConfig::CONFIG)['interceptors']
        );

        $bootloader->addInterceptor($autowire = new Autowire('foo'));
        $this->assertSame(
            ['foo', $autowire],
            $configs->getConfig(EventBusConfig::CONFIG)['interceptors']
        );

        $bootloader->addInterceptor($interceptor = $this->createMock(CoreInterceptorInterface::class));
        $this->assertSame(
            ['foo', $autowire, $interceptor],
            $configs->getConfig(EventBusConfig::CONFIG)['interceptors']
        );
    }
}
