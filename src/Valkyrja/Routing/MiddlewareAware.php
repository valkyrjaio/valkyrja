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

namespace Valkyrja\Routing;

use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Http\Response\Contract\Response;

/**
 * Interface MiddlewareAware.
 *
 * @author Melech Mizrachi
 */
interface MiddlewareAware
{
    /**
     * Determine if a middleware is a group of middleware.
     *
     * @param string $middleware The middleware to check
     *
     * @return bool
     */
    public function isMiddlewareGroup(string $middleware): bool;

    /**
     * Get a middleware group.
     *
     * @param string $middleware The middleware group
     *
     * @return string[]
     */
    public function getMiddlewareGroup(string $middleware): array;

    /**
     * Dispatch middleware.
     *
     * @param ServerRequest $request    The request
     * @param array|null    $middleware [optional] The middleware to dispatch
     *
     * @return ServerRequest|Response
     */
    public function requestMiddleware(ServerRequest $request, array|null $middleware = null): Response|ServerRequest;

    /**
     * Dispatch after request processed middleware.
     *
     * @param ServerRequest $request    The request
     * @param Response      $response   The response
     * @param array|null    $middleware [optional] The middleware to dispatch
     *
     * @return Response
     */
    public function responseMiddleware(ServerRequest $request, Response $response, array|null $middleware = null): Response;

    /**
     * Dispatch terminable middleware.
     *
     * @param ServerRequest $request    The request
     * @param Response      $response   The response
     * @param array|null    $middleware [optional] The middleware to dispatch
     *
     * @return void
     */
    public function terminableMiddleware(ServerRequest $request, Response $response, array|null $middleware = null): void;
}
