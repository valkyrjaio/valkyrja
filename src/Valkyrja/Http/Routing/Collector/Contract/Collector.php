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

namespace Valkyrja\Http\Routing\Collector\Contract;

use Valkyrja\Http\Routing\Data\Contract\Route;

/**
 * Interface Collector.
 *
 * @author Melech Mizrachi
 */
interface Collector
{
    /**
     * Get route attributes.
     *
     * @param class-string ...$classes The classes
     *
     * @return Route[]
     */
    public function getRoutes(string ...$classes): array;
}
