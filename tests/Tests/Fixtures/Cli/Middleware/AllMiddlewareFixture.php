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

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ProcessExitingMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ProcessExitingHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

final class AllMiddlewareFixture implements InputReceivedMiddlewareContract, RouteMatchedMiddlewareContract, RouteDispatchedMiddlewareContract, ThrowableCaughtMiddlewareContract, ProcessExitingMiddlewareContract
{
    public function processExiting(InputContract $input, OutputContract $output, ProcessExitingHandlerContract $handler): void
    {
    }

    public function inputReceived(InputContract $input, InputReceivedHandlerContract $handler): InputContract|OutputContract
    {
        return $input;
    }

    public function routeDispatched(InputContract $input, OutputContract $output, RouteContract $route, RouteDispatchedHandlerContract $handler): OutputContract
    {
        return $output;
    }

    public function routeMatched(InputContract $input, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|OutputContract
    {
        return $route;
    }

    public function throwableCaught(InputContract $input, OutputContract $output, Throwable $throwable, ThrowableCaughtHandlerContract $handler): OutputContract
    {
        return $output;
    }
}
