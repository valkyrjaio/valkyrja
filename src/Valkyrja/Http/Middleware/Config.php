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

use Valkyrja\Config\Config as ParentConfig;
use Valkyrja\Http\Middleware\Constant\ConfigName;
use Valkyrja\Http\Middleware\Constant\EnvName;
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
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::REQUEST_RECEIVED  => EnvName::REQUEST_RECEIVED,
        ConfigName::ROUTE_DISPATCHED  => EnvName::ROUTE_DISPATCHED,
        ConfigName::THROWABLE_CAUGHT  => EnvName::THROWABLE_CAUGHT,
        ConfigName::ROUTE_MATCHED     => EnvName::ROUTE_MATCHED,
        ConfigName::ROUTE_NOT_MATCHED => EnvName::ROUTE_NOT_MATCHED,
        ConfigName::SENDING_RESPONSE  => EnvName::SENDING_RESPONSE,
        ConfigName::TERMINATED        => EnvName::TERMINATED,
    ];

    /**
     * @param class-string<RequestReceivedMiddleware>[] $requestReceived The before middleware
     * @param class-string<RouteDispatchedMiddleware>[] $routeDispatched The dispatched middleware
     * @param class-string<RouteMatchedMiddleware>[]    $routeMatched    The route matched middleware
     * @param class-string<RouteNotMatchedMiddleware>[] $routeNotMatched The route not matched middleware
     * @param class-string<ThrowableCaughtMiddleware>[] $throwableCaught The exception middleware
     * @param class-string<SendingResponseMiddleware>[] $sendingResponse The sending middleware
     * @param class-string<TerminatedMiddleware>[]      $terminated      The terminated middleware
     */
    public function __construct(
        public array $requestReceived = [],
        public array $routeDispatched = [],
        public array $routeMatched = [],
        public array $routeNotMatched = [],
        public array $throwableCaught = [],
        public array $sendingResponse = [],
        public array $terminated = [],
    ) {
    }
}
