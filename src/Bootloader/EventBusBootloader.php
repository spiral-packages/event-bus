<?php

declare(strict_types=1);

namespace Spiral\EventBus\Bootloader;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcherInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Append;
use Spiral\Core\Container\Autowire;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\InterceptableCore;
use Spiral\EventBus\Config\EventBusConfig;
use Spiral\EventBus\EventDispatchCore;
use Spiral\EventBus\EventDispatcher;
use Spiral\Events\ListenerRegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventBusBootloader extends Bootloader
{
    /** array<class-string<CoreInterceptorInterface>> */
    protected const INTERCEPTORS = [];

    protected const SINGLETONS = [
        ListenerRegistryInterface::class => EventDispatcher::class,
        EventDispatcherInterface::class => EventDispatcher::class,
        PsrEventDispatcherInterface::class => EventDispatcher::class,
        EventDispatcher::class => [self::class, 'initEventDispatcher'],
    ];

    /**
     * Register an interceptor for event dispatcher
     *
     * @param class-string<CoreInterceptorInterface>|CoreInterceptorInterface|Autowire $interceptor
     */
    public function addInterceptor(string|CoreInterceptorInterface|Autowire $interceptor): void
    {
        $this->config->modify(
            EventBusConfig::CONFIG,
            new Append('interceptors', null, $interceptor)
        );
    }

    private function initEventDispatcher(
        EventDispatchCore $core,
        EventBusConfig $config,
        ContainerInterface $container
    ): EventDispatcherInterface {
        $interceptableCore = new InterceptableCore($core);
        $interceptors = \array_unique(
            \array_merge(static::INTERCEPTORS, $config->getInterceptors())
        );

        foreach ($interceptors as $interceptor) {
            if (\is_string($interceptor) || $interceptor instanceof Autowire) {
                $interceptor = $container->get($interceptor);
            }

            \assert($interceptor instanceof CoreInterceptorInterface);
            $interceptableCore->addInterceptor($interceptor);
        }

        return new EventDispatcher($interceptableCore);
    }

    public function __construct(
        private readonly ConfiguratorInterface $config
    ) {
    }

    public function init(): void
    {
        $this->config->setDefaults(
            EventBusConfig::CONFIG,
            [
                'interceptors' => []
            ]
        );
    }
}
