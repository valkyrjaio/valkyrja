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

namespace Valkyrja\Cli\Middleware\Data\Contract;

use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;

interface ConfigContract
{
    /** @var class-string<InputReceivedMiddlewareContract>[] */
    public array $inputReceivedMiddleware {
        get;
    }
    /** @var class-string<RouteMatchedMiddlewareContract>[] */
    public array $routeMatchedMiddleware {
        get;
    }
    /** @var class-string<RouteNotMatchedMiddlewareContract>[] */
    public array $routeNotMatchedMiddleware {
        get;
    }
    /** @var class-string<RouteDispatchedMiddlewareContract>[] */
    public array $routeDispatchedMiddleware {
        get;
    }
    /** @var class-string<ThrowableCaughtMiddlewareContract>[] */
    public array $throwableCaughtMiddleware {
        get;
    }
    /** @var class-string<ExitedMiddlewareContract>[] */
    public array $exitedMiddleware {
        get;
    }
}
