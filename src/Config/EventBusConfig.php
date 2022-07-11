<?php

declare(strict_types=1);

namespace Spiral\EventBus\Config;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\InjectableConfig;

final class EventBusConfig extends InjectableConfig
{
    public const CONFIG = 'event-bus';

    protected array$config = [
        'queueConnection' => null,
        'discoverListeners' => true,
        'listeners' => [],
        'interceptors' => [],
    ];

    /** @return array<class-string<CoreInterceptorInterface>> */
    public function getInterceptors(): array
    {
        return (array)($this->config['interceptors'] ?? []);
    }

    /** array<class-string, array<class-string>> */
    public function getListeners(): array
    {
        return (array)($this->config['listeners'] ?? []);
    }

    public function getQueueConnection(): ?string
    {
        return $this->config['queueConnection'] ?? null;
    }

    public function discoverListeners(): bool
    {
        return (bool)($this->config['discoverListeners'] ?? true);
    }
}
