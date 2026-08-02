<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Middleware\Handler;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

/**
 * @extends Handler<RouteMatchedMiddlewareContract>
 */
class RouteMatchedHandler extends Handler implements RouteMatchedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeMatched(InputContract $input, RouteContract $route): RouteContract|OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeMatched($input, $route, $this)
            : $route;
    }
}
