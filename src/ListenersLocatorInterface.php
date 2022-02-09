<?php

declare(strict_types=1);

namespace Spiral\EventBus;

interface ListenersLocatorInterface
{
    /**
     * @return array<class-string, array<array{0: class-string, 1: non-empty-string}>>
     */
    public function getListeners(): array;
}
