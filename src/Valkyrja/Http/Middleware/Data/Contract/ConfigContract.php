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

namespace Valkyrja\Http\Middleware\Data\Contract;

use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;

interface ConfigContract
{
    /** @var class-string<RequestReceivedMiddlewareContract>[] */
    public array $requestReceivedMiddleware {
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
    /** @var class-string<SendingResponseMiddlewareContract>[] */
    public array $sendingResponseMiddleware {
        get;
    }
    /** @var class-string<TerminatedMiddlewareContract>[] */
    public array $terminatedMiddleware {
        get;
    }
}
