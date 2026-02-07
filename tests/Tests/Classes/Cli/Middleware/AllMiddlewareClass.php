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

namespace Valkyrja\Tests\Classes\Cli\Middleware;

use Throwable;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ExitedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Cli\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;

final class AllMiddlewareClass implements InputReceivedMiddlewareContract, RouteMatchedMiddlewareContract, RouteDispatchedMiddlewareContract, ThrowableCaughtMiddlewareContract, ExitedMiddlewareContract
{
    public function exited(InputContract $input, OutputContract $output, ExitedHandlerContract $handler): void
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
