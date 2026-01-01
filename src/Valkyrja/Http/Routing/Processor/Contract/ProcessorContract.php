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

namespace Valkyrja\Http\Routing\Processor\Contract;

use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Interface ProcessorContract.
 *
 * @author Melech Mizrachi
 */
interface ProcessorContract
{
    /**
     * Process a route.
     *
     * @param RouteContract $route The route
     *
     * @return RouteContract
     */
    public function route(RouteContract $route): RouteContract;
}
