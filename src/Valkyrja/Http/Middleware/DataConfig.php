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
use Valkyrja\Config\DataConfig as ParentConfig;
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
class DataConfig extends ParentConfig
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
     * @param class-string<RequestReceivedMiddleware>[] $before          The before middleware
     * @param class-string<RouteDispatchedMiddleware>[] $dispatched      The dispatched middleware
     * @param class-string<ThrowableCaughtMiddleware>[] $exception       The exception middleware
     * @param class-string<RouteMatchedMiddleware>[]    $routeMatched    The route matched middleware
     * @param class-string<RouteNotMatchedMiddleware>[] $routeNotMatched The route not matched middleware
     * @param class-string<SendingResponseMiddleware>[] $sending         The sending middleware
     * @param class-string<TerminatedMiddleware>[]      $terminated      The terminated middleware
     */
    public function __construct(
        public array $before = [],
        public array $dispatched = [],
        public array $exception = [],
        public array $routeMatched = [],
        public array $routeNotMatched = [],
        public array $sending = [],
        public array $terminated = [],
    ) {
    }
}
