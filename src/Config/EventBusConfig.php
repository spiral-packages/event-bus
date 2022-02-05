<?php

declare(strict_types=1);

namespace Spiral\EventBus\Config;

use Spiral\Core\InjectableConfig;

final class EventBusConfig extends InjectableConfig
{
    public const CONFIG = 'event-bus';

    protected $config = [
        'queueConnection' => null,
    ];

    public function getQueueConnection(): ?string
    {
        return $this->config['queueConnection'] ?? null;
    }
}
