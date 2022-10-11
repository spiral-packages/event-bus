<?php

declare(strict_types=1);

namespace Spiral\EventBus\Config;

use Spiral\Core\Container\Autowire;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\InjectableConfig;

final class EventBusConfig extends InjectableConfig
{
    public const CONFIG = 'event-bus';

    protected array $config = [
        'interceptors' => [],
    ];

    /** @return class-string<CoreInterceptorInterface>[]|CoreInterceptorInterface[]|Autowire[] */
    public function getInterceptors(): array
    {
        return (array)($this->config['interceptors'] ?? []);
    }
}
