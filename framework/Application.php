<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja;

use Throwable;

use Valkyrja\Config\Config;
use Valkyrja\Config\Env;
use Valkyrja\Container\Container;
use Valkyrja\Contracts\Application as ApplicationContract;
use Valkyrja\Contracts\Config\Config as ConfigContract;
use Valkyrja\Contracts\Config\Env as EnvContract;
use Valkyrja\Contracts\Container\Container as ContainerContract;
use Valkyrja\Contracts\Http\JsonResponse;
use Valkyrja\Contracts\Http\Request;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder;
use Valkyrja\Contracts\Routing\Router;
use Valkyrja\Contracts\View\View;
use Valkyrja\Debug\Debug;
use Valkyrja\Debug\ExceptionHandler;
use Valkyrja\Http\Exceptions\HttpException;

/**
 * Class Application
 *
 * @package Valkyrja
 *
 * @author  Melech Mizrachi
 */
class Application implements ApplicationContract
{
    /**
     * Get the instance of the application.
     *
     * @var \Valkyrja\Contracts\Application
     */
    protected static $app;

    /**
     * Get the instance of the container.
     *
     * @var \Valkyrja\Contracts\Container\Container
     */
    protected $container;

    /**
     * Application config
     *
     * @var \Valkyrja\Contracts\Config\Config
     */
    protected $config;

    /**
     * Is the app using a compiled version?
     *
     * @var bool
     */
    protected $isCompiled = false;

    /**
     * Application constructor.
     *
     * @param \Valkyrja\Contracts\Container\Container $container [optional] The container to use
     * @param \Valkyrja\Contracts\Config\Config       $config    [optional] The config to use
     */
    public function __construct(?ContainerContract $container = null, ?ConfigContract $config = null)
    {
        // Check to ensure a correct container was passed
        if (! $container instanceof ContainerContract) {
            // Use the Valkyrja container
            $container = new Container();
        }

        // Check to ensure a correct env was passed
        if (! $config instanceof ConfigContract) {
            // Use the Valkyrja config and env
            $config = new Config(new Env());
        }

        // If debug is on, enable debug handling
        if ($config->app->debug) {
            // Debug to output exceptions
            Debug::enable(E_ALL, $config->app->debug);
        }

        // Set the app static
        static::$app = $this;

        // Set the container within the application
        $this->container = $container;
        // Set the config within the application
        $this->config = $config;

        // Set the application instance in the container
        $container->instance(ApplicationContract::class, $this);
        // Bootstrap the container
        $container->bootstrap();

        // Set the timezone for the application to run within
        $this->setTimezone();
    }

    /**
     * Get the application instance.
     *
     * @return \Valkyrja\Contracts\Application
     */
    public static function app(): ApplicationContract
    {
        return static::$app;
    }

    /**
     * Get the application version.
     *
     * @return string
     */
    public function version(): string
    {
        return static::VERSION;
    }

    /**
     * Get the container instance.
     *
     * @return \Valkyrja\Contracts\Container\Container
     */
    public function container(): ContainerContract
    {
        return $this->container;
    }

    /**
     * Get the config class instance.
     *
     * @return \Valkyrja\Contracts\Config\Config|\Valkyrja\Config\Config|\config\Config
     */
    public function config(): ConfigContract
    {
        return $this->config;
    }

    /**
     * Get environment variables.
     *
     * @return \Valkyrja\Contracts\Config\Env|\Valkyrja\Config\Env||config|Env
     */
    public function env(): EnvContract
    {
        return $this->config()->env;
    }

    /**
     * Return the router instance from the container.
     *
     * @return \Valkyrja\Contracts\Routing\Router
     */
    public function router(): Router
    {
        return $this->container->get(Router::class);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content [optional] The content to set
     * @param int    $status  [optional] The status code to set
     * @param array  $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function response(string $content = '', int $status = 200, array $headers = []): Response
    {
        /** @var Response $response */
        $response = $this->container->get(Response::class);

        return $response->create($content, $status, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param array $data    [optional] An array of data
     * @param int   $status  [optional] The status code to set
     * @param array $headers [optional] The headers to set
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function responseJson(array $data = [], int $status = 200, array $headers = []): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = $this->container->get(JsonResponse::class);

        return $response->create('', $status, $headers)->setData($data);
    }

    /**
     * Return a new response from the application.
     *
     * @return \Valkyrja\Contracts\Http\ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder
    {
        return $this->container->get(ResponseBuilder::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function view(string $template = '', array $variables = []): View
    {
        return $this->container->get(
            View::class,
            [
                $template,
                $variables,
            ]
        );
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return $this->config()->app->env ?? 'production';
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return string
     */
    public function debug(): string
    {
        return $this->config()->app->debug ?? false;
    }

    /**
     * Is twig enabled?
     *
     * @return bool
     */
    public function isTwigEnabled(): bool
    {
        return $this->config()->views->twig->enabled ?? false;
    }

    /**
     * Set the timezone for the application process.
     *
     * @return void
     */
    public function setTimezone(): void
    {
        date_default_timezone_set($this->config()->app->timezone ?? 'UTC');
    }

    /**
     * Get whether the application is using a compiled version.
     *
     * @return bool
     */
    public function isCompiled(): bool
    {
        return $this->isCompiled;
    }

    /**
     * Set the application as using compiled.
     *
     * @return void
     */
    public function setCompiled(): void
    {
        $this->isCompiled = true;
    }

    /**
     * Abort the application due to error.
     *
     * @param int    $statusCode The status code to use
     * @param string $message    [optional] The Exception message to throw
     * @param array  $headers    [optional] The headers to send
     * @param int    $code       [optional] The Exception code
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     */
    public function abort(int $statusCode = 404, string $message = '', array $headers = [], int $code = 0): void
    {
        throw new HttpException($statusCode, $message, null, $headers, $code);
    }

    /**
     * Handle a request.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     *
     * @return \Valkyrja\Contracts\Http\Response
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     * @throws \Valkyrja\Http\Exceptions\InvalidControllerException
     */
    public function handle(Request $request): Response
    {
        try {
            $response = $this->router()->dispatch($request);
        }
        catch (Throwable $exception) {
            $handler = new ExceptionHandler($this->config()->app->debug);
            $response = $handler->getResponse($exception);
        }

        // Dispatch the request and return it
        return $response;
    }

    /**
     * Run the application.
     *
     * @return void
     *
     * @throws \Valkyrja\Contracts\Http\Exceptions\HttpException
     * @throws \Valkyrja\Http\Exceptions\InvalidControllerException
     */
    public function run(): void
    {
        /** @var Request $request */
        $request = $this->container()->get(Request::class);

        // Handle the request and send the response
        $this->handle($request)->send();
    }

    /**
     * Register a service provider.
     *
     * @param string $serviceProvider The service provider
     *
     * @return void
     */
    public function register(string $serviceProvider): void
    {
        // Create a new instance of the service provider
        new $serviceProvider($this);
    }
}
