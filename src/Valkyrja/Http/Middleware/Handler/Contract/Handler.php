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

use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;

/**
 * Interface Handler.
 *
 * @author Melech Mizrachi
 *
 * @template Middleware of RequestReceivedMiddleware|SendingResponseMiddleware|RouteMatchedMiddleware|RouteNotMatchedMiddleware|RouteDispatchedMiddleware|ThrowableCaughtMiddleware|TerminatedMiddleware
 */
interface Handler
{
    /**
     * @param class-string<Middleware> ...$middleware The middleware to add
     */
    public function add(string ...$middleware): void;
}
