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
use Valkyrja\Annotation\Annotator;
use Valkyrja\Client\Client;
use Valkyrja\Console\Console;
use Valkyrja\Console\Kernel as ConsoleKernel;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Crypt\Crypt;
use Valkyrja\Filesystem\Filesystem;
use Valkyrja\Http\Enums\StatusCode;
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

use function func_num_args;

/**
 * Trait Helpers.
 *
 * @author Melech Mizrachi
 *
 * @property Container $container
 */
trait ContainerHelpers
{
    /**
     * Return the annotations instance from the container.
     *
     * @return Annotator
     */
    public function annotations(): Annotator
    {
        return self::$container->getSingleton(Contract::ANNOTATOR);
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
