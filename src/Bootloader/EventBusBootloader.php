<?php

declare(strict_types=1);

namespace Spiral\EventBus\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container;
use Spiral\EventBus\Config\EventBusConfig;
use Spiral\EventBus\EventHandler;
use Spiral\EventBus\QueueableInterface;
use Spiral\Queue\QueueConnectionProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventBusBootloader extends Bootloader
{
    protected const LISTENS = [];

    protected const SINGLETONS = [
        EventDispatcherInterface::class => EventDispatcher::class,
    ];

    protected function listens(): array
    {
        return static::LISTENS;
    }

    public function __construct(private ConfiguratorInterface $config)
    {
    }

    public function boot(EnvironmentInterface $env): void
    {
        $this->config->setDefaults(
            EventBusConfig::CONFIG,
            [
                'queueConnection' => $env->get('EVENT_BUS_QUEUE_CONNECTION'),
            ]
        );
    }

    public function start(EventDispatcherInterface $dispatcher, EventBusConfig $config, Container $container): void
    {
        $events = $this->listens();

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $dispatcher->addListener(
                    $event,
                    static function (object $event, string $eventName) use ($config, $listener, $container) {
                        $connection = is_a($listener, QueueableInterface::class)
                            ? $config->getQueueConnection()
                            : 'sync';

                        $queue = $container->get(QueueConnectionProviderInterface::class)
                            ->getConnection($connection);

                        $queue->push(EventHandler::class, [
                            'listener' => $listener,
                            'method' => 'handle',
                            'event' => $event,
                            'eventName' => $eventName,
                        ]);
                    }
                );
            }
        }
    }
}
