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

namespace Valkyrja\Application\Helpers;

use Valkyrja\Annotation\Annotator;
use Valkyrja\Cache\Cache;
use Valkyrja\Client\Client;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Http\Exceptions\HttpException;
use Valkyrja\Http\Exceptions\HttpRedirectException;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\Kernel;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Request;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Log\Logger;
use Valkyrja\Mail\Mail;
use Valkyrja\ORM\ORM;
use Valkyrja\Path\PathGenerator;
use Valkyrja\Path\PathParser;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Routing\Router;
use Valkyrja\Session\Session;
use Valkyrja\View\View;

use function func_num_args;

/**
 * Trait ApplicationHelpersTrait.
 *
 * @author Melech Mizrachi
 *
 * @property Container $container
 */
trait ApplicationHelpersTrait
{
    /**
     * Get the environment with which the application is running in.
     *
     * @return string
     */
    public function environment(): string
    {
        return self::$config['app']['env'];
    }

    /**
     * Whether the application is running in debug mode or not.
     *
     * @return bool
     */
    public function debug(): bool
    {
        return self::$config['app']['debug'];
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
        throw new self::$config->app->httpException(
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
     * @return Annotator
     */
    public function annotator(): Annotator
    {
        return self::$container->getSingleton(Annotator::class);
    }

    /**
     * Return the cache instance from the container.
     *
     * @return Cache
     */
    public function cache(): Cache
    {
        return self::$container->getSingleton(Cache::class);
    }

    /**
     * Return the client instance from the container.
     *
     * @return Client
     */
    public function client(): Client
    {
        return self::$container->getSingleton(Client::class);
    }

    /**
     * Return the console instance from the container.
     *
     * @return Console
     */
    public function console(): Console
    {
        return self::$container->getSingleton(Console::class);
    }

    /**
     * Return the console kernel instance from the container.
     *
     * @return ConsoleKernel
     */
    public function consoleKernel(): ConsoleKernel
    {
        return self::$container->getSingleton(ConsoleKernel::class);
    }

    /**
     * Return the crypt instance from the container.
     *
     * @return Crypt
     */
    public function crypt(): Crypt
    {
        return self::$container->getSingleton(Crypt::class);
    }

    /**
     * Return the filesystem instance from the container.
     *
     * @return Filesystem
     */
    public function filesystem(): Filesystem
    {
        return self::$container->getSingleton(Filesystem::class);
    }

    /**
     * Return the kernel instance from the container.
     *
     * @return Kernel
     */
    public function kernel(): Kernel
    {
        return self::$container->getSingleton(Kernel::class);
    }

    /**
     * Return the logger instance from the container.
     *
     * @return Logger
     */
    public function logger(): Logger
    {
        return self::$container->getSingleton(Logger::class);
    }

    /**
     * Return the mail instance from the container.
     *
     * @return Mail
     */
    public function mail(): Mail
    {
        return self::$container->getSingleton(Mail::class);
    }

    /**
     * Return the ORM manager instance from the container.
     *
     * @return ORM
     */
    public function orm(): ORM
    {
        return self::$container->getSingleton(ORM::class);
    }

    /**
     * Return the path generator instance from the container.
     *
     * @return PathGenerator
     */
    public function pathGenerator(): PathGenerator
    {
        return self::$container->getSingleton(PathGenerator::class);
    }

    /**
     * Return the path parser instance from the container.
     *
     * @return PathParser
     */
    public function pathParser(): PathParser
    {
        return self::$container->getSingleton(PathParser::class);
    }

    /**
     * Return the reflector instance from the container.
     *
     * @return Reflector
     */
    public function reflector(): Reflector
    {
        return self::$container->getSingleton(Reflector::class);
    }

    /**
     * Return the request instance from the container.
     *
     * @return Request
     */
    public function request(): Request
    {
        return self::$container->getSingleton(Request::class);
    }

    /**
     * Return the router instance from the container.
     *
     * @return Router
     */
    public function router(): Router
    {
        return self::$container->getSingleton(Router::class);
    }

    /**
     * Return a new response from the application.
     *
     * @param string|null $content    [optional] The content to set
     * @param int|null    $statusCode [optional] The status code to set
     * @param array|null  $headers    [optional] The headers to set
     *
     * @return Response
     */
    public function response(string $content = null, int $statusCode = null, array $headers = null): Response
    {
        return $this->responseFactory()->createResponse($content, $statusCode, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param array|null $data       [optional] An array of data
     * @param int|null   $statusCode [optional] The status code to set
     * @param array|null $headers    [optional] The headers to set
     *
     * @return JsonResponse
     */
    public function json(array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
        return $this->responseFactory()->createJsonResponse($data, $statusCode, $headers);
    }

    /**
     * Return a new json response from the application.
     *
     * @param string|null $uri        [optional] The URI to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirect(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse
    {
        return $this->responseFactory()->createRedirectResponse($uri, $statusCode, $headers);
    }

    /**
     * Return a new redirect response from the application for a given route.
     *
     * @param string     $route      The route to match
     * @param array|null $parameters [optional] Any parameters to set for dynamic routes
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirectRoute(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse {
        return $this->responseFactory()->route($route, $parameters, $statusCode, $headers);
    }

    /**
     * Return a new response from the application.
     *
     * @return ResponseFactory
     */
    public function responseFactory(): ResponseFactory
    {
        return self::$container->getSingleton(ResponseFactory::class);
    }

    /**
     * Return the session.
     *
     * @return Session
     */
    public function session(): Session
    {
        return self::$container->getSingleton(Session::class);
    }

    /**
     * Helper function to get a new view.
     *
     * @param string|null $template  [optional] The template to use
     * @param array       $variables [optional] The variables to use
     *
     * @return View
     */
    public function view(string $template = null, array $variables = []): View
    {
        /** @var View $view */
        $view = self::$container->getSingleton(View::class);

        if (func_num_args() === 0) {
            return $view;
        }

        return $view->make($template, $variables);
    }
}
