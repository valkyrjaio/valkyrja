<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container;

use Closure;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;

use Psr\Log\LoggerInterface;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Annotations\AnnotationsParser;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Annotations\Annotations as AnnotationsContract;
use Valkyrja\Contracts\Annotations\AnnotationsParser as AnnotationsParserContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Http\Client as ClientContract;
use Valkyrja\Contracts\Http\JsonResponse as JsonResponseContract;
use Valkyrja\Contracts\Http\RedirectResponse as RedirectResponseContract;
use Valkyrja\Contracts\Http\Request as RequestContract;
use Valkyrja\Contracts\Http\Response as ResponseContract;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Contracts\Logger\Logger as LoggerContract;
use Valkyrja\Contracts\Routing\Annotations\RouteAnnotations as RouteAnnotationsContract;
use Valkyrja\Contracts\Routing\Router as RouterContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Http\Client;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Http\ResponseCode;
use Valkyrja\Logger\Enums\LogLevel;
use Valkyrja\Logger\Logger;
use Valkyrja\Routing\Annotations\RouteAnnotations;
use Valkyrja\Routing\Router;
use Valkyrja\View\View;

/**
 * Class Container
 *
 * @package Valkyrja\Container
 *
 * @author  Melech Mizrachi
 */
class Container implements ContainerContract
{
    /**
     * Service container for dependency injection.
     *
     * @var array
     */
    protected $serviceContainer = [];

    /**
     * Set the service container for dependency injection.
     *
     * @param array $serviceContainer The service container array to set
     *
     * @return void
     */
    public function setServiceContainer(array $serviceContainer): void
    {
        // The application has already bootstrapped the container so merge to avoid clearing
        $this->serviceContainer = array_merge($this->serviceContainer, $serviceContainer);
    }

    /**
     * Set an abstract in the service container.
     *
     * @param string   $abstract  The abstract to use as the key
     * @param \Closure $closure   The instance to set
     * @param bool     $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function bind(string $abstract, Closure $closure, bool $singleton = false): void
    {
        $this->set($abstract, $closure, $singleton);
    }

    /**
     * Set an abstract as a singleton in the service container.
     *
     * @param string   $abstract The abstract to use as the key
     * @param \Closure $closure  The instance to set
     *
     * @return void
     */
    public function singleton(string $abstract, Closure $closure): void
    {
        $this->bind($abstract, $closure, true);
    }

    /**
     * Set an object in the service container.
     *
     * @param string $abstract The abstract to use as the key
     * @param object $instance The instance to set
     *
     * @return void
     */
    public function instance(string $abstract, $instance): void
    {
        $this->set($abstract, $instance, true, true);
    }

    /**
     * Set an alias in the service container.
     *
     * @param string $abstract  The abstract to use as the key
     * @param string $alias     The instance to set
     * @param bool   $singleton Whether this abstract should be treated as a singleton
     *
     * @return void
     */
    public function alias(string $abstract, string $alias, bool $singleton = false): void
    {
        $this->set($abstract, $alias, $singleton);
    }

    /**
     * Set an abstract in the service container.
     *
     * @param string $abstract  The abstract to use as the key
     * @param mixed  $closure   The instance to set
     * @param bool   $singleton [optional] Whether this abstract should be treated as a singleton
     * @param bool   $made      [optional] Whether this abstract is already made into an object or not
     *
     * @return void
     */
    protected function set(string $abstract, $closure, bool $singleton = false, bool $made = false): void
    {
        $this->serviceContainer[$abstract] = [
            $closure,
            $singleton,
            $made,
        ];
    }

    /**
     * Get an abstract from the container.
     *
     * @param string $abstract  The abstract to get
     * @param array  $arguments [optional] Arguments to pass
     *
     * @return mixed
     */
    public function get(string $abstract, array $arguments = [])
    {
        // If the abstract is set in the service container
        if (isset($this->serviceContainer[$abstract])) {
            // If the object is already made
            if ($this->serviceContainer[$abstract][2]) {
                // Return it
                return $this->serviceContainer[$abstract][0];
            }

            // Set the container item for ease of use here
            $containerItem = $this->serviceContainer[$abstract][0];

            // Check if this container item is a callable function
            if (is_callable($containerItem)) {
                // Run the callable function
                $containerItem = $containerItem(...$arguments);

                // If this is a singleton
                if ($this->serviceContainer[$abstract][1] === true) {
                    // Set the result in the service container for the next request
                    $this->serviceContainer[$abstract][0] = $containerItem;
                    // Set this singleton to made
                    $this->serviceContainer[$abstract][2] = true;
                }
            }
            // If the container item is a string
            else if (is_string($containerItem)) {
                // Set the container item as a new instance
                $containerItem = new $containerItem(...$arguments);

                // If this is a singleton
                if ($this->serviceContainer[$abstract][1] === true) {
                    // Set the result in the service container for the next request
                    $this->serviceContainer[$abstract][0] = $containerItem;
                    // Set this singleton to made
                    $this->serviceContainer[$abstract][2] = true;
                }
            }

            // Return the container item
            return $containerItem;
        }

        return new $abstract(...$arguments);
    }

    /**
     * Check whether an abstract is set in the container.
     *
     * @param string $abstract The abstract to check for
     *
     * @return bool
     */
    public function bound(string $abstract): bool
    {
        return isset($this->serviceContainer[$abstract]);
    }

    /**
     * Bootstrap the container.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        $this->bootstrapAnnotationsParser();
        $this->bootstrapAnnotations();
        $this->bootstrapRequest();
        $this->bootstrapRequest();
        $this->bootstrapResponse();
        $this->bootstrapJsonResponse();
        $this->bootstrapRedirectResponse();
        $this->bootstrapResponseBuilder();
        $this->bootstrapRouter();
        $this->bootstrapRouteAnnotations();
        $this->bootstrapView();
        $this->bootstrapClient();
        $this->bootstrapLoggerInterface();
        $this->bootstrapLogger();
    }

    /**
     * Bootstrap the annotations parser.
     *
     * @return void
     */
    protected function bootstrapAnnotationsParser(): void
    {
        $this->singleton(
            AnnotationsParserContract::class,
            function () {
                return new AnnotationsParser();
            }
        );
    }

    /**
     * Bootstrap the annotations.
     *
     * @return void
     */
    protected function bootstrapAnnotations(): void
    {
        $this->singleton(
            AnnotationsContract::class,
            function () {
                /** @var AnnotationsParserContract $parser */
                $parser = $this->get(AnnotationsParserContract::class);

                return new Annotations($parser);
            }
        );
    }

    /**
     * Bootstrap the request.
     *
     * @return void
     */
    protected function bootstrapRequest(): void
    {
        $this->singleton(
            RequestContract::class,
            function () {
                return Request::createFromGlobals();
            }
        );
    }

    /**
     * Bootstrap the response.
     *
     * @return void
     */
    protected function bootstrapResponse(): void
    {
        $this->bind(
            ResponseContract::class,
            function (string $content = '', int $status = ResponseCode::HTTP_OK, array $headers = []) {
                return new Response($content, $status, $headers);
            }
        );
    }

    /**
     * Bootstrap the json response.
     *
     * @return void
     */
    protected function bootstrapJsonResponse(): void
    {
        $this->bind(
            JsonResponseContract::class,
            function (string $content = '', int $status = ResponseCode::HTTP_OK, array $headers = []) {
                return new JsonResponse($content, $status, $headers);
            }
        );
    }

    /**
     * Bootstrap the redirect response.
     *
     * @return void
     */
    protected function bootstrapRedirectResponse(): void
    {
        $this->bind(
            RedirectResponseContract::class,
            function (string $content = '', int $status = ResponseCode::HTTP_FOUND, array $headers = []) {
                return new RedirectResponse($content, $status, $headers);
            }
        );
    }

    /**
     * Bootstrap the response builder.
     *
     * @return void
     */
    protected function bootstrapResponseBuilder(): void
    {
        $this->singleton(
            ResponseBuilderContract::class,
            function () {
                /** @var \Valkyrja\Contracts\Application $app */
                $app = $this->get(Application::class);

                return new ResponseBuilder($app);
            }
        );
    }

    /**
     * Bootstrap the router.
     *
     * @return void
     */
    protected function bootstrapRouter(): void
    {
        $this->singleton(
            RouterContract::class,
            function () {
                /** @var \Valkyrja\Contracts\Application $app */
                $app = $this->get(Application::class);

                return new Router($app);
            }
        );
    }

    /**
     * Bootstrap the route annotations.
     *
     * @return void
     */
    protected function bootstrapRouteAnnotations(): void
    {
        $this->singleton(
            RouteAnnotationsContract::class,
            function () {
                /** @var AnnotationsParserContract $parser */
                $parser = $this->get(AnnotationsParserContract::class);

                return new RouteAnnotations($parser);
            }
        );
    }

    /**
     * Bootstrap the view.
     *
     * @return void
     */
    protected function bootstrapView(): void
    {
        $this->bind(
            ViewContract::class,
            function (string $template = '', array $variables = []) {
                /** @var \Valkyrja\Contracts\Application $app */
                $app = $this->get(Application::class);

                return new View($app, $template, $variables);
            }
        );
    }

    /**
     * Bootstrap the client.
     *
     * @return void
     */
    protected function bootstrapClient(): void
    {
        $this->singleton(
            ClientContract::class,
            function () {
                return new Client();
            }
        );
    }

    /**
     * Bootstrap the logger interface.
     *
     * @return void
     */
    protected function bootstrapLoggerInterface(): void
    {
        $this->singleton(
            LoggerInterface::class,
            function () {
                /** @var \Valkyrja\Contracts\Application $app */
                $app = $this->get(Application::class);

                $logger = new MonologLogger($app->config()->logger->name);

                $logger->pushHandler(new StreamHandler($app->config()->logger->filePath, LogLevel::DEBUG));

                return $logger;
            }
        );
    }

    /**
     * Bootstrap the logger.
     *
     * @return void
     */
    protected function bootstrapLogger(): void
    {
        $this->singleton(
            LoggerContract::class,
            function () {
                /** @var LoggerInterface $logger */
                $logger = $this->get(LoggerInterface::class);

                return new Logger($logger);
            }
        );
    }
}
