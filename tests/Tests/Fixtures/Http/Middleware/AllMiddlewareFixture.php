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

namespace Valkyrja\Tests\Fixtures\Http\Middleware;

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

final class AllMiddlewareFixture implements RequestReceivedMiddlewareContract, RouteMatchedMiddlewareContract, RouteDispatchedMiddlewareContract, SendingResponseMiddlewareContract, ThrowableCaughtMiddlewareContract, ResponseSentMiddlewareContract
{
    public function requestReceived(ServerRequestContract $request, RequestReceivedHandlerContract $handler): ServerRequestContract|ResponseContract
    {
        return $request;
    }

    public function routeDispatched(
        ServerRequestContract $request,
        ResponseContract $response,
        RouteContract $route,
        RouteDispatchedHandlerContract $handler
    ): ResponseContract {
        return $response;
    }

    public function routeMatched(ServerRequestContract $request, RouteContract $route, RouteMatchedHandlerContract $handler): RouteContract|ResponseContract
    {
        return $route;
    }

    public function sendingResponse(ServerRequestContract $request, ResponseContract $response, SendingResponseHandlerContract $handler): ResponseContract
    {
        return $response;
    }

    public function responseSent(ServerRequestContract $request, ResponseContract $response, ResponseSentHandlerContract $handler): void
    {
    }

    public function throwableCaught(
        ServerRequestContract $request,
        ResponseContract $response,
        Throwable $throwable,
        ThrowableCaughtHandlerContract $handler
    ): ResponseContract {
        return $response;
    }
}
