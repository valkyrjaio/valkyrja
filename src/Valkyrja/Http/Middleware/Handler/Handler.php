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

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\Contract\Handler2Contract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteDispatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandlerContract;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandlerContract;
use Valkyrja\Http\Middleware\Handler\Enum\MiddlewareType;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

class Handler implements Handler2Contract, RequestReceivedHandlerContract, RouteDispatchedHandlerContract, RouteMatchedHandlerContract, RouteNotMatchedHandlerContract, SendingResponseHandlerContract, TerminatedHandlerContract, ThrowableCaughtHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function add(MiddlewareType $type, string ...$middleware): void
    {
        // TODO: Implement add() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequestContract $request): ResponseContract|ServerRequestContract
    {
        // TODO: Implement requestReceived() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeDispatched(ServerRequestContract $request, ResponseContract $response, RouteContract $route): ResponseContract
    {
        // TODO: Implement routeDispatched() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeMatched(ServerRequestContract $request, RouteContract $route): RouteContract|ResponseContract
    {
        // TODO: Implement routeMatched() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response): ResponseContract
    {
        // TODO: Implement routeNotMatched() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function sendingResponse(ServerRequestContract $request, ResponseContract $response): ResponseContract
    {
        // TODO: Implement sendingResponse() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function terminated(ServerRequestContract $request, ResponseContract $response): void
    {
        // TODO: Implement terminated() method.
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function throwableCaught(ServerRequestContract $request, ResponseContract $response, Throwable $throwable): ResponseContract
    {
        // TODO: Implement throwableCaught() method.
    }
}
