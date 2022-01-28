<?php

declare(strict_types=1);

namespace Spiral\EventBus\Commands;

use Spiral\Console\Command;

class EventBusCommand extends Command
{
    protected const NAME = 'event-bus';
    protected const DESCRIPTION = 'My command';
    protected const ARGUMENTS = [];

    public function perform(): int
    {
    }
}
