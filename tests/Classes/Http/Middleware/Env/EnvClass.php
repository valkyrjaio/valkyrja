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

use Valkyrja\Application\Env;
use Valkyrja\Tests\Classes\Http\Middleware\RequestReceivedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteDispatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\RouteNotMatchedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\SendingResponseMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\TerminatedMiddlewareClass;
use Valkyrja\Tests\Classes\Http\Middleware\ThrowableCaughtMiddlewareClass;

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 */
class EnvClass extends Env
{
    public const HTTP_MIDDLEWARE_REQUEST_RECEIVED  = [RequestReceivedMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_ROUTE_DISPATCHED  = [RouteDispatchedMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_THROWABLE_CAUGHT  = [ThrowableCaughtMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_ROUTE_MATCHED     = [RouteMatchedMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = [RouteNotMatchedMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_SENDING_RESPONSE  = [SendingResponseMiddlewareClass::class];
    public const HTTP_MIDDLEWARE_TERMINATED        = [TerminatedMiddlewareClass::class];
}
