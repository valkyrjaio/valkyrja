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
use Valkyrja\Container\CoreComponent;
use Valkyrja\Debug\ExceptionHandler;
use Valkyrja\Routing\Router;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Kernel.
 *
 * @author Melech Mizrachi
 */
class KernelImpl implements Kernel
{
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
        $this->app->container()->singleton(Request::class, $request);

        try {
            $response = $this->router->dispatch($request);
        } catch (Throwable $exception) {
            $handler  = new ExceptionHandler($this->app->debug());
            $response = $handler->getResponse($exception);
        }

        $this->app->events()->trigger('Kernel.handled', [$request, $response]);

        // Dispatch the request and return it
        return $response;
    }

    /**
     * Terminate the kernel request.
     *
     * @param \Valkyrja\Http\Request  $request  The request
     * @param \Valkyrja\Http\Response $response The response
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

        // Handle the request and send the response
        $response = $this->handle($request)->send();

        // Terminate the application
        $this->terminate($request, $response);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::KERNEL,
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
            CoreComponent::KERNEL,
            new static($app, $app->router())
        );
    }
}
