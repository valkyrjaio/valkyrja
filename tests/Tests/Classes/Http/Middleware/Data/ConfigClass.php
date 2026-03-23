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

namespace Valkyrja\Tests\Classes\Http\Middleware\Data;

use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Data\Contract\ConfigContract;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteNotMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;

final class ConfigClass implements ConfigContract
{
    /**
     * @param class-string<RequestReceivedMiddlewareContract>[] $requestReceivedMiddleware
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware
     * @param class-string<RouteNotMatchedMiddlewareContract>[] $routeNotMatchedMiddleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware
     * @param class-string<SendingResponseMiddlewareContract>[] $sendingResponseMiddleware
     * @param class-string<TerminatedMiddlewareContract>[]      $terminatedMiddleware
     */
    public function __construct(
        public array $requestReceivedMiddleware = [RequestReceivedMiddlewareClass::class],
        public array $routeMatchedMiddleware = [RouteMatchedMiddlewareClass::class],
        public array $routeNotMatchedMiddleware = [RouteNotMatchedMiddlewareClass::class],
        public array $routeDispatchedMiddleware = [RouteDispatchedMiddlewareClass::class],
        public array $throwableCaughtMiddleware = [ThrowableCaughtMiddlewareClass::class],
        public array $sendingResponseMiddleware = [SendingResponseMiddlewareClass::class],
        public array $terminatedMiddleware = [TerminatedMiddlewareClass::class],
    ) {
    }
}
