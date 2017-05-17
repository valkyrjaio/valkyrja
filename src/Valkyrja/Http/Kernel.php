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
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\Kernel as KernelContract;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Debug\ExceptionHandler;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class Kernel implements KernelContract
{
    /**
     * The application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected $app;

    /**
     * The router.
     *
     * @var \Valkyrja\Contracts\Routing\Router
     */
    protected $router;

    /**
     * Kernel constructor.
     *
     * @param \Valkyrja\Contracts\Application    $application The application
     * @param \Valkyrja\Contracts\Routing\Router $router      The router
     */
    public function __construct(Application $application, Router $router)
    {
        $this->app    = $application;
        $this->router = $router;
    }

    /**
     * Handle a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function handle(Request $request): Response
    {
        $this->app->container()->singleton(Request::class, $request);

        try {
            $response = $this->router->dispatch($request);
        } catch (Throwable $exception) {
            $handler  = new ExceptionHandler($this->app->config()->app->debug);
            $response = $handler->getResponse($exception);
        }

        $this->app->events()->trigger('Kernel.handled', [$request, $response]);

        // Dispatch the request and return it
        return $response;
    }

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Contracts\Http\Request  $request  The request
     * @param \Valkyrja\Contracts\Http\Response $response The response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->app->events()->trigger('Kernel.terminate', [$request, $response]);
    }

    /**
     * Run the kernel.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @throws \Valkyrja\Http\Exceptions\HttpException
     *
     * @return void
     */
    public function run(Request $request = null): void
    {
        // If no request was passed get the bootstrapped definition
        if (null === $request) {
            $request = $this->app->container()->get(Request::class);
        }

        // Handle the request and send the response
        $response = $this->handle($request)->send();

        // Terminate the application
        $this->terminate($request, $response);
    }
}
