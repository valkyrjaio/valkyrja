<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Http;

use Throwable;
use Valkyrja\Application;
use Valkyrja\Debug\ExceptionHandler;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;
use Valkyrja\Support\Middleware\MiddlewareAwareTrait;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class KernelImpl implements Kernel
{
    use MiddlewareAwareTrait;
    use Provides;

    /**
     * The application.
     *
     * @var \Valkyrja\Application
     */
    protected $app;

    /**
     * The router.
     *
     * @var \Valkyrja\Routing\Router
     */
    protected $router;

    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Application    $application The application
     * @param \Valkyrja\Routing\Router $router      The router
     */
    public function __construct(Application $application, Router $router)
    {
        $this->app    = $application;
        $this->router = $router;

        self::$middleware       = $application->config()['routing']['middleware'];
        self::$middlewareGroups = $application->config()['routing']['middlewareGroups'];
    }

    /**
     * Handle a request.
     *
     * @param \Valkyrja\Http\Request $request The request
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return \Valkyrja\Http\Response
     */
    public function handle(Request $request): Response
    {
        try {
            $response = $this->dispatchRouter($request);
        } catch (Throwable $exception) {
            $response = $this->getExceptionResponse($exception);
        }

        // Dispatch the after request handled middleware and return the response
        return $this->responseMiddleware($request, $response);
    }

    /**
     * Dispatch the request via the router.
     *
     * @param \Valkyrja\Http\Request $request The request
     *
     * @return \Valkyrja\Http\Response
     */
    protected function dispatchRouter(Request $request): Response
    {
        // Set the request object in the container
        $this->app->container()->singleton(Request::class, $request);

        // Dispatch the before request handled middleware
        $request = $this->requestMiddleware($request);

        return $this->router->dispatch($request);
    }

    /**
     * Get a response from an exception.
     *
     * @param \Throwable $exception The exception
     *
     * @return \Valkyrja\Http\Response
     */
    protected function getExceptionResponse(Throwable $exception): Response
    {
        $handler = new ExceptionHandler($this->app->debug());

        return $handler->getResponse($exception);
    }

    /**
     * Terminate the request.
     *
     * @param \Valkyrja\Http\Request  $request  The request
     * @param \Valkyrja\Http\Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        // Dispatch the terminable middleware
        $this->terminableMiddleware($request, $response);

        /* @var Route $route */
        $route = $this->app->container()->getSingleton(Route::class);

        // If the dispatched route has middleware
        if (null !== $route->getMiddleware()) {
            // Terminate each middleware
            $this->terminableMiddleware($request, $response, $route->getMiddleware());
        }
    }

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Http\Request $request The request
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function run(Request $request = null): void
    {
        // If no request was passed get the bootstrapped definition
        if (null === $request) {
            $request = $this->app->container()->getSingleton(Request::class);
        }

        // Handle the request, dispatch the after request middleware, and send the response
        $response = $this->handle($request)->send();

        // Terminate the application
        $this->terminate($request, $response);
    }

    /**
     * Get the application.
     *
     * @return \Valkyrja\Application
     */
    protected function getApplication(): Application
    {
        return $this->app;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Kernel::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            Kernel::class,
            new static($app, $app->router())
        );
    }
}
