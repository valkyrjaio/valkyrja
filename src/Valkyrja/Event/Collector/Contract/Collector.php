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

namespace Valkyrja\Event\Collector\Contract;

use Valkyrja\Event\Data\Contract\Listener;

/**
 * Interface Collector.
 *
 * @author Melech Mizrachi
 */
interface Collector
{
    /**
     * Get the listeners.
     *
     * @param class-string ...$classes The classes
     *
     * @return Listener[]
     */
    public function getListeners(string ...$classes): array;
}
