<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Application\Helpers;

use InvalidArgumentException;
use Valkyrja\Annotation\Annotations;
use Valkyrja\Application\Application;
use Valkyrja\Client\Client;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Dispatcher\Dispatcher;
use Valkyrja\Env\Env;
use Valkyrja\Event\Events;
use Valkyrja\Exception\ExceptionHandler;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\Exceptions\InvalidStatusCodeException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseBuilder;
use Valkyrja\Logger\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\EntityManager;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

/**
 * Trait Helpers.
 *
 * @author Melech Mizrachi
 */
trait Helpers
{
    /**
     * Get the instance of the application.
     *
     * @var Application
     */
    protected static Application $app;

    /**
     * Application env.
     *
     * @var string
     */
    protected static string $env;

    /**
     * Application config.
     *
     * @var array
     */
    protected static array $config;

    /**
     * Get the instance of the container.
     *
     * @var Container
     */
    protected static Container $container;

    /**
     * Get the instance of the dispatcher.
     *
     * @var Dispatcher
     */
    protected static Dispatcher $dispatcher;

    /**
     * Get the instance of the events.
     *
     * @var Events
     */
    protected static Events $events;

    /**
     * Get the instance of the exception handler.
     *
     * @var ExceptionHandler
     */
    protected static ExceptionHandler $exceptionHandler;

    /**
     * Get the application instance.
     *
     * @return Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * Get an environment variable.
     *
     * @param string $key     [optional] The variable to get
     * @param mixed  $default [optional] The default value to return
     *
     * @return mixed
     */
    public static function env(string $key = null, $default = null)
    {
        // If there was no variable requested
        if (null === $key) {
            // Return the env class
            return static::getEnv();
        }

        // If the env has this variable defined and the variable isn't null
        if (
            defined(static::getEnv() . '::' . $key)
            && null !== $env = constant(static::getEnv() . '::' . $key)
        ) {
            // Return the variable
            return $env;
        }

        // Otherwise return the default
        return $default;
    }

    /**
     * Get the environment variables class.
     *
     * @return string
     */
    public static function getEnv(): string
    {
        return self::$env ?? (self::$env = Env::class);
    }

    /**
     * Set the environment variables class.
     *
     * @param string $env [optional] The env file to use
     *
     * @return void
     */
    public static function setEnv(string $env = null): void
    {
        // Set the env class to use
        self::$env = ($env ?? self::$env ?? Env::class);
    }

    /**
     * Get the config.
     *
     * @param string $key     [optional] The key to get
     * @param mixed  $default [optional] The default value if the key is not found
     *
     * @return mixed
     */
    public function config(string $key = null, $default = null)
    {
        // If no key was specified
        if (null === $key) {
            // Return all the entire config
            return self::$config;
        }

        // Explode the keys on period
        $keys = explode(ConfigKeyPart::SEP, $key);
        // Set the config to return
        $config = self::$config;

        // Iterate through the keys
        foreach ($keys as $configItem) {
            // Trying to get the item from the config
            // or load the default
            $config = $config[$configItem] ?? $default;

            // If the item was not found, might as well return out from here
            // instead of continuing to iterate through the remaining keys
            if ($default === $config) {
                return $default;
            }
        }

        // do while($current !== $default);

        // Return the found config
        return $config;
    }

    /**
     * Add to the global config array.
     *
     * @param array $newConfig The new config to add
     *
     * @return void
     */
    public function addConfig(array $newConfig): void
    {
        // Set the config within the application
        self::$config = array_replace_recursive(self::$config, $newConfig);
    }

    /**
     * Get the container instance.
     *
     * @return Container
     */
    public function container(): Container
    {
        return self::$container;
    }

    /**
     * Get the dispatcher instance.
     *
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return self::$dispatcher;
    }

    /**
     * Get the events instance.
     *
     * @return Events
     */
    public function events(): Events
    {
        return self::$events;
    }

    /**
     * Get the exception handler instance.
     *
     * @return ExceptionHandler
     */
    public function exceptionHandler(): ExceptionHandler
    {
        return self::$exceptionHandler;
    }

    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return self::$config[ConfigKeyPart::APP][ConfigKeyPart::ENV];
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return self::$config[ConfigKeyPart::APP][ConfigKeyPart::DEBUG];
    }

    /**
     * Abort the application due to error.
     *
     * @param int      $statusCode The status code to use
     * @param string   $message    [optional] The Exception message to throw
     * @param array    $headers    [optional] The headers to send
     * @param int      $code       [optional] The Exception code
     * @param Response $response   [optional] The Response to send
     *
     * @throws HttpException
     *
     * @return void
     */
    public function abort(
        int $statusCode = StatusCode::NOT_FOUND,
        string $message = '',
        array $headers = [],
        int $code = 0,
        Response $response = null
    ): void {
        throw new self::$config[ConfigKeyPart::APP][ConfigKeyPart::HTTP_EXCEPTION_CLASS](
            $statusCode,
            $message,
            null,
            $headers,
            $code,
            $response
        );
    }

    /**
     * Redirect to a given uri, and abort the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws HttpRedirectException
     *
     * @return void
     */
    public function redirectTo(string $uri = null, int $statusCode = StatusCode::FOUND, array $headers = []): void
    {
        throw new HttpRedirectException($statusCode, $uri, null, $headers, 0);
    }

    /**
     * Return the annotations instance from the container.
     *
     * @return Annotations
     */
    public function annotations(): Annotations
    {
        return self::$container->getSingleton(Contract::ANNOTATIONS);
    }

    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    public function client(): Client
    {
        return self::$container->getSingleton(Contract::CLIENT);
    }

    /**
     * Return the console instance from the container.
     *
     * @return Console
     */
    public function console(): Console
    {
        return self::$container->getSingleton(Contract::CONSOLE);
    }

    /**
     * Return the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return self::$container->getSingleton(Contract::CONSOLE_KERNEL);
    }

    /**
     * Return the crypt instance from the container.
     *
     * @return Crypt
     */
    public function crypt(): Crypt
    {
        return self::$container->getSingleton(Contract::CRYPT);
    }

    /**
     * Return the entity manager instance from the container.
     *
     * @return EntityManager
     */
    public function entityManager(): EntityManager
    {
        return self::$container->getSingleton(Contract::ENTITY_MANAGER);
    }

    /**
     * Return the filesystem instance from the container.
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem
    {
        return self::$container->getSingleton(Contract::FILESYSTEM);
    }

    /**
     * Return the kernel instance from the container.
     *
     * @return Kernel
     */
    public function kernel(): Kernel
    {
        return self::$container->getSingleton(Contract::KERNEL);
    }

    /**
     * Return the logger instance from the container.
     *
     * @return Logger
     */
    public function logger(): Logger
    {
        return self::$container->getSingleton(Contract::LOGGER);
    }

    /**
     * Return the mail instance from the container.
     *
     * @return Mail
     */
    public function mail(): Mail
    {
        return self::$container->getSingleton(Contract::MAIL);
    }

    /**
     * Return the path generator instance from the container.
     *
     * @return PathGenerator
     */
    public function pathGenerator(): PathGenerator
    {
        return self::$container->getSingleton(Contract::PATH_GENERATOR);
    }

    /**
     * Return the path parser instance from the container.
     *
     * @return PathParser
     */
    public function pathParser(): PathParser
    {
        return self::$container->getSingleton(Contract::PATH_PARSER);
    }

    /**
     * Return the request instance from the container.
     *
     * @return Request
     */
    public function request(): Request
    {
        return self::$container->getSingleton(Contract::REQUEST);
    }

    /**
     * Return the router instance from the container.
     *
     * @return Router
     */
    public function router(): Router
    {
        return self::$container->getSingleton(Contract::ROUTER);
    }

    /**
     * Return a new response from the application.
     *
     * @param string $content    [optional] The content to set
     * @param int    $statusCode [optional] The status code to set
     * @param array  $headers    [optional] The headers to set
     *
     * @throws InvalidArgumentException
     *
     * @return Response
     */
    public function response(string $content = '', int $statusCode = StatusCode::OK, array $headers = []): Response
    {
        /** @var Response $response */
        $response = self::$container->getSingleton(Contract::RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::create($content, $statusCode, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param array $data       [optional] An array of data
     * @param int   $statusCode [optional] The status code to set
     * @param array $headers    [optional] The headers to set
     *
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function json(array $data = [], int $statusCode = StatusCode::OK, array $headers = []): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = self::$container->getSingleton(Contract::JSON_RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createJson('', $statusCode, $headers, $data);
    }

    /**
     * Return a new json response from the application.
     *
     * @param string $uri        [optional] The URI to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws InvalidStatusCodeException
     * @throws InvalidArgumentException
     *
     * @return RedirectResponse
     */
    public function redirect(
        string $uri = null,
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        /** @var RedirectResponse $response */
        $response = self::$container->getSingleton(Contract::REDIRECT_RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createRedirect($uri, $statusCode, $headers);
    }

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic
     *                           routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws InvalidStatusCodeException
     * @throws InvalidArgumentException
     *
     * @return RedirectResponse
     */
    public function redirectRoute(
        string $route,
        array $parameters = [],
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        // Get the uri from the router using the route and parameters
        $uri = $this->router()->routeUrl($route, $parameters);

        return $this->redirect($uri, $statusCode, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return ResponseBuilder
     */
    public function responseBuilder(): ResponseBuilder
    {
        return self::$container->getSingleton(Contract::RESPONSE_BUILDER);
    }

    /**
     * Return the session.
     *
     * @return Session
     */
    public function session(): Session
    {
        return self::$container->getSingleton(Contract::SESSION);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string $template  [optional] The template to use
     * @param array  $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = '', array $variables = []): View
    {
        /** @var View $view */
        $view = self::$container->getSingleton(Contract::VIEW);

        if (func_num_args() === 0) {
            return $view;
        }

        return $view->make($template, $variables);
    }
}
