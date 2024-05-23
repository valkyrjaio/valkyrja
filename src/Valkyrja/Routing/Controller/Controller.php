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

namespace Valkyrja\Routing\Controller;

use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Routing\Contract\Router;
use Valkyrja\Routing\Model\Contract\Route;

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
     * @var ServerRequest
     */
    private static ServerRequest $request;

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
     * @return ServerRequest
     */
    protected static function getRequest(): ServerRequest
    {
        return self::$request ??= self::getContainer()->getSingleton(ServerRequest::class);
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
