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

namespace Valkyrja\Tests\Classes\Http\Middleware\Env;

use Valkyrja\Application\Env\Env;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\TerminatedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteNotMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;

/**
 * Class Env.
 */
class EnvClass extends Env
{
    /************************************************************
     *
     * Http Middleware component env variables.
     *
     ************************************************************/
    /** @var class-string<RequestReceivedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_REQUEST_RECEIVED = [RequestReceivedMiddlewareClass::class];
    /** @var class-string<RouteDispatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_DISPATCHED = [RouteDispatchedMiddlewareClass::class];
    /** @var class-string<RouteMatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_THROWABLE_CAUGHT = [ThrowableCaughtMiddlewareClass::class];
    /** @var class-string<RouteNotMatchedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_MATCHED = [RouteMatchedMiddlewareClass::class];
    /** @var class-string<ThrowableCaughtMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = [RouteNotMatchedMiddlewareClass::class];
    /** @var class-string<SendingResponseMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_SENDING_RESPONSE = [SendingResponseMiddlewareClass::class];
    /** @var class-string<TerminatedMiddlewareContract>[]|null */
    public const array|null HTTP_MIDDLEWARE_TERMINATED = [TerminatedMiddlewareClass::class];
}
