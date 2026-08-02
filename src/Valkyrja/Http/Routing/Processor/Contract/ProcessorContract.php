<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Processor\Contract;

use Valkyrja\Http\Routing\Data\Contract\RouteContract;

interface ProcessorContract
{
    /**
     * Process a route.
     *
     * @param RouteContract $route The route
     */
    public function route(RouteContract $route): RouteContract;
}
