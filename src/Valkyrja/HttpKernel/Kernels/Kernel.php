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
use Valkyrja\Container\Support\Provides;
use Valkyrja\Event\Events;
use Valkyrja\Http\Enums\StatusCode;
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
    use Provides;

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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config = $container->getSingleton('config');

        $container->setSingleton(
            Contract::class,
            new static(
                $container,
                $container->getSingleton(Events::class),
                $container->getSingleton(Router::class),
                (array) $config['routing'],
                $config['app']['debug']
            )
        );
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

        /* @var Route $route */
        $route = $this->container->getSingleton(Route::class);

        // If the dispatched route has middleware
        if (null !== $route->getMiddleware()) {
            // Terminate each middleware
            $this->terminableMiddleware($request, $response, $route->getMiddleware());
        }

        // Trigger an event for kernel handled
        $this->events->trigger(HttpKernelTerminate::class, [$request, $response]);
    }

    /**
     * Run the kernel.
     *
     * @param Request|null $request The request
     *
     * @throws Throwable
     *
     * @return void
     */
    public function run(Request $request = null): void
    {
        // If no request was passed get the bootstrapped definition
        if (null === $request) {
            $request = $this->container->getSingleton(Request::class);
        }

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
        $request = $this->requestMiddleware($request);

        // Set the returned request in the container
        $this->container->setSingleton(Request::class, $request);

        return $this->router->dispatch($request);
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
        $logger = $this->container->getSingleton(Logger::class);

        $logger->error((string) $exception);
    }
}
