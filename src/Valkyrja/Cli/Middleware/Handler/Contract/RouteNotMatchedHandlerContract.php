<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Handler\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;

/**
 * @extends HandlerContract<RouteNotMatchedMiddlewareContract>
 */
interface RouteNotMatchedHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for after a route has not been matched.
     */
    public function routeNotMatched(InputContract $input, OutputContract $output): OutputContract;
}
