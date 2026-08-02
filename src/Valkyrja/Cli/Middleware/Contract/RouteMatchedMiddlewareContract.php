<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

interface RouteMatchedMiddlewareContract
{
    /**
     * Middleware handler for after a route has been matched but before it has been dispatched.
     */
    public function routeMatched(InputContract $input, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|OutputContract;
}
