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

/**
 * Class to test events for unit testing.
 *
 * @author Melech Mizrachi
 */
class StoppableEventClass extends DispatchCollectableEventClass implements StoppableEventInterface
{
    public function isPropagationStopped(): bool
    {
        return true;
    }
}
