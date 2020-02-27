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

namespace Valkyrja\Http\Factories;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Container\Container;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory as ResponseFactoryContract;
use Valkyrja\Support\Providers\Provides;

use function func_num_args;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements ResponseFactoryContract
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * ResponseBuilder constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app       = $app;
        $this->container = $app->container();
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ResponseFactoryContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(ResponseFactoryContract::class, new static($app));
    }

    /**
     * Create a response.
     *
     * @param string|null $content    [optional] The response content
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function createResponse(string $content = null, int $statusCode = null, array $headers = null): Response
    {
        /** @var Response $response */
        $response = $this->container->getSingleton(Contract::RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createResponse($content, $statusCode, $headers);
    }

    /**
     * Create a JSON response.
     *
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function createJsonResponse(array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
        /** @var JsonResponse $response */
        $response = $this->container->getSingleton(Contract::JSON_RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createJsonResponse($data, $statusCode, $headers);
    }

    /**
     * Create a JSONP response.
     *
     * @param string     $callback   The jsonp callback
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function createJsonpResponse(
        string $callback,
        array $data = null,
        int $statusCode = null,
        array $headers = null
    ): JsonResponse {
        return $this->createJsonResponse($data, $statusCode, $headers)->withCallback($callback);
    }

    /**
     * Create a redirect response.
     *
     * @param string|null $uri        [optional] The uri to redirect to
     * @param int|null    $statusCode [optional] The response status code
     * @param array|null  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function createRedirectResponse(string $uri = null, int $statusCode = null, array $headers = null): RedirectResponse
    {
        /** @var RedirectResponse $response */
        $response = $this->container->getSingleton(Contract::REDIRECT_RESPONSE);

        if (func_num_args() === 0) {
            return $response;
        }

        return $response::createRedirectResponse($uri, $statusCode, $headers);
    }

    /**
     * Redirect to a named route response builder.
     *
     * @param string     $route      The route to match
     * @param array|null $parameters [optional] Any parameters to set for dynamic routes
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function route(
        string $route,
        array $parameters = null,
        int $statusCode = null,
        array $headers = null
    ): RedirectResponse {
        // Get the uri from the router using the route and parameters
        $uri = $this->app->router()->getUrl($route, $parameters);

        return $this->createRedirectResponse($uri, $statusCode, $headers);
    }

    /**
     * View response builder.
     *
     * @param string     $template   The view template to use
     * @param array|null $data       [optional] The view data
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function view(string $template, array $data = null, int $statusCode = null, array $headers = null): Response
    {
        $content = $this->app->view()->make($template, $data)->render();

        return $this->createResponse($content, $statusCode, $headers);
    }
}
