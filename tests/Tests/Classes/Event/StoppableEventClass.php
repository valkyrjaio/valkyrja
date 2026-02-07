<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Classes\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Valkyrja\Event\Contract\DispatchCollectableEventContract;

/**
 * Class to test events for unit testing.
 */
final class StoppableEventClass implements DispatchCollectableEventContract, StoppableEventInterface
{
    private array $dispatches = [];

    public function addDispatch(mixed $dispatch): void
    {
        $this->dispatches[] = $dispatch;
    }

    public function getDispatches(): array
    {
        return $this->dispatches;
    }

    public function isPropagationStopped(): bool
    {
        return true;
    }
}
