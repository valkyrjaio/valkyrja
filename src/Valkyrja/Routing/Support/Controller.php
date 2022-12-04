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

namespace Valkyrja\Routing\Support;

use Valkyrja\Container\Container;
use Valkyrja\Http\Request;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;

/**
 * Abstract Class Controller.
 *
 * @author Melech Mizrachi
 */
abstract class Controller
{
    /**
     * The container.
     *
     * @var Container
     */
    private static Container $container;

    /**
     * The request.
     *
     * @var Request
     */
    private static Request $request;

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
     * The route.
     *
     * @var Route
     */
    private static Route $route;

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
     * The request.
     *
     * @return Request
     */
    protected static function getRequest(): Request
    {
        return self::$request ??= self::getContainer()->getSingleton(Request::class);
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
     * @return Route
     */
    protected static function getRoute(): Route
    {
        return self::$route ??= self::getContainer()->getSingleton(Route::class);
    }
}
