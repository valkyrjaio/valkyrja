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

namespace Valkyrja\HttpKernel\Kernels;

use RuntimeException;
use Throwable;
use Valkyrja\Container\Container;
use Valkyrja\Event\Events;
use Valkyrja\Http\Constants\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\HttpKernel\Events\HttpKernelHandled;
use Valkyrja\HttpKernel\Events\HttpKernelTerminate;
use Valkyrja\HttpKernel\Kernel as Contract;
use Valkyrja\Log\Logger;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Router;
use Valkyrja\Routing\Support\MiddlewareAwareTrait;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class Kernel implements Contract
{
    use MiddlewareAwareTrait;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The events.
     *
     * @var Events
     */
    protected Events $events;

    /**
     * The request.
     *
     * @var Request
     */
    protected Request $request;

    /**
     * The router.
     *
     * @var Router
     */
    protected Router $router;

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * Whether to run in debug.
     *
     * @var bool
     */
    protected bool $debug;

    /**
     * Kernel constructor.
     *
     * @param Container $container The container
     * @param Events    $events    The events
     * @param Router    $router    The router
     * @param array     $config    The config
     * @param bool      $debug     [optional] Whether to run in debug
     */
    public function __construct(
        Container $container,
        Events $events,
        Router $router,
        array $config,
        bool $debug = false
    ) {
        $this->container = $container;
        $this->events    = $events;
        $this->router    = $router;
        $this->config    = $config;
        $this->debug     = $debug;

        self::$middleware       = $config['middleware'];
        self::$middlewareGroups = $config['middlewareGroups'];
    }

    /**
     * Handle a request.
     *
     * @param Request $request The request
     *
     * @throws Throwable
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $this->request = $request;

        try {
            $response = $this->dispatchRouter($request);
        } catch (Throwable $exception) {
            $response = $this->getExceptionResponse($exception);

            // Log the error
            $this->logException($exception);
        }

        // Dispatch the after request handled middleware and return the response
        $response = $this->responseMiddleware($request, $response);

        // Set the returned response in the container
        $this->container->setSingleton(Response::class, $response);
        // Trigger an event for kernel handled
        $this->events->trigger(HttpKernelHandled::class, [$request, $response]);

        return $response;
    }

    /**
     * Send the response.
     *
     * @param Response $response
     *
     * @throws RuntimeException
     *
     * @return static
     */
    public function send(Response $response): self
    {
        $response->send();

        return $this;
    }

    /**
     * Terminate the request.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        // Dispatch the terminable middleware
        $this->terminableMiddleware($request, $response);

        // If a route was dispatched
        if ($this->container->has(Route::class)) {
            // Terminate the route middleware
            $this->terminateRoute($request, $response);
        }

        // Trigger an event for kernel handled
        $this->events->trigger(HttpKernelTerminate::class, [$request, $response]);
    }

    /**
     * Run the kernel.
     *
     * @param Request $request The request
     *
     * @throws Throwable
     *
     * @return void
     */
    public function run(Request $request): void
    {
        // Handle the request, dispatch the after request middleware
        $response = $this->handle($request);

        // Send the response
        $this->send($response);
        // Terminate the application
        $this->terminate($request, $response);
    }

    /**
     * Dispatch the request via the router.
     *
     * @param Request $request The request
     *
     * @return Response
     */
    protected function dispatchRouter(Request $request): Response
    {
        // Set the request object in the container
        $this->container->setSingleton(Request::class, $request);

        // Dispatch the before request handled middleware
        $requestAfterMiddleware = $this->requestMiddleware($request);

        // If the return value after middleware is a response return it
        if ($requestAfterMiddleware instanceof Response) {
            return $requestAfterMiddleware;
        }

        // Set the returned request in the container
        $this->container->setSingleton(Request::class, $requestAfterMiddleware);

        return $this->router->dispatch($requestAfterMiddleware);
    }

    /**
     * Get a response from an exception.
     *
     * @param Throwable $exception The exception
     *
     * @throws Throwable
     *
     * @return Response
     */
    protected function getExceptionResponse(Throwable $exception): Response
    {
        if ($this->debug) {
            throw $exception;
        }

        /** @var ResponseFactory $responseFactory */
        $responseFactory = $this->container->getSingleton(ResponseFactory::class);

        // If no response has been set and there is a template with the error code
        if ($exception instanceof HttpException) {
            try {
                // Set the response as the error template
                return $exception->getResponse()
                    ?? $responseFactory->view(
                        'errors/' . $exception->getStatusCode(),
                        null,
                        $exception->getStatusCode()
                    );
            } catch (Throwable $exception) {
            }
        }

        return $responseFactory->view('errors/500', null, StatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Log an error.
     *
     * @param Throwable $exception
     *
     * @return void
     */
    protected function logException(Throwable $exception): void
    {
        /** @var Logger $logger */
        $logger     = $this->container->getSingleton(Logger::class);
        $url        = $this->request->getUri()->getPath();
        $logMessage = "Kernel Error\nUrl: {$url}";

        $logger->exception($exception, $logMessage);
    }

    /**
     * Terminate a route's middleware.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return void
     */
    protected function terminateRoute(Request $request, Response $response): void
    {
        /* @var Route $route */
        $route = $this->container->getSingleton(Route::class);

        // If the dispatched route has middleware
        if (null !== $route->getMiddleware()) {
            // Terminate each middleware
            $this->terminableMiddleware($request, $response, $route->getMiddleware());
        }
    }
}
