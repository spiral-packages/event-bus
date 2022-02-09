<?php

declare(strict_types=1);

namespace Spiral\EventBus;

interface ListenersLocatorInterface
{
    /**
     * @return array<class-string, array<class-string, non-empty-string>>
     */
    public function getListeners(): iterable;
}
