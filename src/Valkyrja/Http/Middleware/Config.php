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

namespace Valkyrja\Http\Middleware;

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Config as Model;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envKeys = [
        'before'          => EnvKey::HTTP_MIDDLEWARE_BEFORE,
        'dispatched'      => EnvKey::HTTP_MIDDLEWARE_DISPATCHED,
        'exception'       => EnvKey::HTTP_MIDDLEWARE_EXCEPTION,
        'routeMatched'    => EnvKey::HTTP_MIDDLEWARE_ROUTE_MATCHED,
        'routeNotMatched' => EnvKey::HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED,
        'sending'         => EnvKey::HTTP_MIDDLEWARE_SENDING,
        'terminated'      => EnvKey::HTTP_MIDDLEWARE_TERMINATED,
    ];

    /**
     * @var class-string<RequestReceivedMiddleware>[]
     */
    public array $before;

    /**
     * @var class-string<RouteDispatchedMiddleware>[]
     */
    public array $dispatched;

    /**
     * @var class-string<ThrowableCaughtMiddleware>[]
     */
    public array $exception;

    /**
     * @var class-string<RouteMatchedMiddleware>[]
     */
    public array $routeMatched;

    /**
     * @var class-string<RouteNotMatchedMiddleware>[]
     */
    public array $routeNotMatched;

    /**
     * @var class-string<SendingResponseMiddleware>[]
     */
    public array $sending;

    /**
     * @var class-string<TerminatedMiddleware>[]
     */
    public array $terminated;
}
