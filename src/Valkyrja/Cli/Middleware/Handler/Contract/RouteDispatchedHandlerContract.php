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

namespace Valkyrja\Cli\Middleware\Handler\Contract;

use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * @extends HandlerContract<RouteDispatchedMiddlewareContract>
 */
interface RouteDispatchedHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for after a route is dispatched.
     */
    public function routeDispatched(InputContract $input, OutputContract $output, RouteContract $route): OutputContract;
}
