<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Cli\Middleware;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Trait\MiddlewareCounterTrait;

final class RouteNotMatchedMiddlewareFixture implements RouteNotMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    #[Override]
    public function routeNotMatched(InputContract $input, OutputContract $output, RouteNotMatchedHandlerContract $handler): OutputContract
    {
        $this->updateCounter();

        return $handler->routeNotMatched($input, $output);
    }
}
