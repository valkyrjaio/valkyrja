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

namespace Valkyrja\Cli\Routing\Collector\Contract;

use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

interface CollectorContract
{
    /**
     * Get the commands.
     *
     * @param class-string ...$classes The classes
     *
     * @return RouteContract[]
     */
    public function getRoutes(string ...$classes): array;
}
