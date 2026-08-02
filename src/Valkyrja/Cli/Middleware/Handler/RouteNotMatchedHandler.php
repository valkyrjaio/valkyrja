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
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Abstract\Handler;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;

/**
 * @extends Handler<RouteNotMatchedMiddlewareContract>
 */
class RouteNotMatchedHandler extends Handler implements RouteNotMatchedHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(InputContract $input, OutputContract $output): OutputContract
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->routeNotMatched($input, $output, $this)
            : $output;
    }
}
