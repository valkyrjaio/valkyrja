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

namespace Valkyrja\Http;

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
     * @param Request    $request    The request
     * @param array|null $middleware [optional] The middleware to dispatch
     *
     * @return mixed
     */
    public function requestMiddleware(Request $request, array $middleware = null);

    /**
     * Dispatch after request processed middleware.
     *
     * @param Request    $request    The request
     * @param Response   $response   The response
     * @param array|null $middleware [optional] The middleware to dispatch
     *
     * @return mixed
     */
    public function responseMiddleware(Request $request, Response $response, array $middleware = null);

    /**
     * Dispatch terminable middleware.
     *
     * @param Request    $request    The request
     * @param Response   $response   The response
     * @param array|null $middleware [optional] The middleware to dispatch
     *
     * @return void
     */
    public function terminableMiddleware(Request $request, Response $response, array $middleware = null): void;
}
