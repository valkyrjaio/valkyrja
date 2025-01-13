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

use Valkyrja\Tests\Classes\Http\Middleware\TestRequestReceivedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteDispatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteMatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestRouteNotMatchedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestSendingResponseMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestTerminatedMiddleware;
use Valkyrja\Tests\Classes\Http\Middleware\TestThrowableCaughtMiddleware;

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 */
class Env extends \Valkyrja\Application\Env
{
    public const HTTP_MIDDLEWARE_BEFORE            = [TestRequestReceivedMiddleware::class];
    public const HTTP_MIDDLEWARE_DISPATCHED        = [TestRouteDispatchedMiddleware::class];
    public const HTTP_MIDDLEWARE_EXCEPTION         = [TestThrowableCaughtMiddleware::class];
    public const HTTP_MIDDLEWARE_ROUTE_MATCHED     = [TestRouteMatchedMiddleware::class];
    public const HTTP_MIDDLEWARE_ROUTE_NOT_MATCHED = [TestRouteNotMatchedMiddleware::class];
    public const HTTP_MIDDLEWARE_SENDING           = [TestSendingResponseMiddleware::class];
    public const HTTP_MIDDLEWARE_TERMINATED        = [TestTerminatedMiddleware::class];
}
