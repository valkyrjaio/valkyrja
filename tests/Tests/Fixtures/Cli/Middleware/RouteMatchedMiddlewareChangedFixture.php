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
use Valkyrja\Cli\Interaction\Output\Output;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Tests\Fixtures\Cli\Middleware\Trait\MiddlewareCounterTrait;

final class RouteMatchedMiddlewareChangedFixture implements RouteMatchedMiddlewareContract
{
    use MiddlewareCounterTrait;

    #[Override]
    public function routeMatched(InputContract $input, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|OutputContract
    {
        $this->updateCounter();

        // Return an output instead of calling the handler to simulate early exit
        return new Output();
    }
}
