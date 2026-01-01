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

use Valkyrja\Event\Contract\DispatchCollectableEventContract;

/**
 * Class to test events for unit testing.
 *
 * @author Melech Mizrachi
 */
class DispatchCollectableEventClass implements DispatchCollectableEventContract
{
    protected array $dispatches = [];

    public function addDispatch(mixed $dispatch): void
    {
        $this->dispatches[] = $dispatch;
    }

    public function getDispatches(): array
    {
        return $this->dispatches;
    }
}
