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

namespace Valkyrja\Routing\Middleware;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Routing\Contract\Router;
use Valkyrja\Routing\Model\Contract\Route;

/**
 * Abstract Class Middleware.
 *
 * @author Melech Mizrachi
 */
abstract class Middleware
{
    /**
     * The matched route.
     *
     * @var Route
     */
    public static Route $route;

    /**
     * The container.
     *
     * @var Container
     */
    private static Container $container;

    /**
     * The response factory.
     *
     * @var ResponseFactory
     */
    private static ResponseFactory $responseFactory;

    /**
     * The router.
     *
     * @var Router
     */
    private static Router $router;

    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param ServerRequest $request The request
     *
     * @return ServerRequest|Response
     */
    public static function before(ServerRequest $request): ServerRequest|Response
    {
        // Do logic using the request before it is processed by the controller action, here

        return $request;
    }

    /**
     * Middleware handler for after a request is dispatched.
     *
     * @param ServerRequest $request  The request
     * @param Response      $response The response
     *
     * @return Response
     */
    public static function after(ServerRequest $request, Response $response): Response
    {
        // Modify the response after its been processed by the controller action, here

        return $response;
    }

    /**
     * Middleware handler run when the application is terminating.
     *
     * @param ServerRequest $request  The request
     * @param Response      $response The response
     *
     * @return void
     */
    public static function terminate(ServerRequest $request, Response $response): void
    {
        // Do stuff after termination (after the response has been sent) here
    }

    /**
     * Get the container service.
     *
     * @return Container
     */
    protected static function getContainer(): Container
    {
        return self::$container ??= \Valkyrja\container();
    }

    /**
     * Get the response factory service.
     *
     * @return ResponseFactory
     */
    protected static function getResponseFactory(): ResponseFactory
    {
        return self::$responseFactory ??= self::getContainer()->getSingleton(ResponseFactory::class);
    }

    /**
     * Get the router service.
     *
     * @return Router
     */
    protected static function getRouter(): Router
    {
        return self::$router ??= self::getContainer()->getSingleton(Router::class);
    }

    /**
     * Get the matched route.
     *
     *  NOTE: This will only be instantiated and available to middlewares' before methods that are set to a route, and
     *  not to global middleware that run on every request. It will be available for all middlewares' after and
     *  terminate assuming a route was matched
     *
     * @return Route|null
     */
    protected static function getRoute(): Route|null
    {
        if (self::getContainer()->isSingleton(Route::class)) {
            return self::$route ??= self::getContainer()->getSingleton(Route::class);
        }

        return null;
    }
}
