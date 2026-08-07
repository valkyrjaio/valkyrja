<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Event;

use Override;
use Psr\EventDispatcher\StoppableEventInterface;
use Valkyrja\Event\Contract\DispatchCollectableEventContract;

/**
 * Class to test events for unit testing.
 */
final class StoppableEventFixture implements DispatchCollectableEventContract, StoppableEventInterface
{
    /** @var array<int, mixed> */
    private array $dispatches = [];

    #[Override]
    public function addDispatch(mixed $dispatch): void
    {
        $this->dispatches[] = $dispatch;
    }

    #[Override]
    public function getDispatches(): array
    {
        return $this->dispatches;
    }

    #[Override]
    public function isPropagationStopped(): bool
    {
        return true;
    }
}
