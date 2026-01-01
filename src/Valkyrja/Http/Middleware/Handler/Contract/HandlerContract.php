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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;

/**
 * @template Middleware of RequestReceivedMiddlewareContract|SendingResponseMiddlewareContract|RouteMatchedMiddlewareContract|RouteNotMatchedMiddlewareContract|RouteDispatchedMiddlewareContract|ThrowableCaughtMiddlewareContract|TerminatedMiddlewareContract
 */
interface HandlerContract
{
    /**
     * @param class-string<Middleware> ...$middleware The middleware to add
     */
    public function add(string ...$middleware): void;
}
