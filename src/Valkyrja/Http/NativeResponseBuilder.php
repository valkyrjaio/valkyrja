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

namespace Valkyrja\Http;

use InvalidArgumentException;
use Valkyrja\Application\Application;
use Valkyrja\Http\Enums\StatusCode;
use Valkyrja\Support\Providers\Provides;

/**
 * Class ResponseBuilder.
 *
 * @author Melech Mizrachi
 */
class NativeResponseBuilder implements ResponseBuilder
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * ResponseBuilder constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ResponseBuilder::class,
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
        $app->container()->singleton(ResponseBuilder::class, new static($app));
    }

    /**
     * Make a new instance of Response.
     *
     * @param string $content    [optional] The response content
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function make(string $content = '', int $statusCode = StatusCode::OK, array $headers = []): Response
    {
        return $this->app->response($content, $statusCode, $headers);
    }

    /**
     * View response builder.
     *
     * @param string $template   The view template to use
     * @param array  $data       [optional] The view data
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return Response
     */
    public function view(
        string $template,
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): Response {
        $content = $this->app->view()->make($template, $data)->render();

        return $this->make($content, $statusCode, $headers);
    }

    /**
     * Json response builder.
     *
     * @param array $data       [optional] The data to set
     * @param int   $statusCode [optional] The response status code
     * @param array $headers    [optional] An array of response headers
     *
     * @return JsonResponse
     */
    public function json(array $data = [], int $statusCode = StatusCode::OK, array $headers = []): JsonResponse
    {
        return $this->app->json($data, $statusCode, $headers);
    }

    /**
     * JsonP response builder.
     *
     * @param string $callback   The jsonp callback
     * @param array  $data       [optional] The data to set
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @throws InvalidArgumentException
     *
     * @return JsonResponse
     */
    public function jsonp(
        string $callback,
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): JsonResponse {
        return $this->json($data, $statusCode, $headers)->setCallback($callback);
    }

    /**
     * Redirect to response builder.
     *
     * @param string $uri        [optional] The uri to redirect to
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function redirect(
        string $uri = '/',
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        return $this->app->redirect($uri, $statusCode, $headers);
    }

    /**
     * Redirect to a named route response builder.
     *
     * @param string $route      The route to match
     * @param array  $parameters [optional] Any parameters to set for dynamic routes
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function route(
        string $route,
        array $parameters = [],
        int $statusCode = StatusCode::FOUND,
        array $headers = []
    ): RedirectResponse {
        return $this->app->redirectRoute($route, $parameters, $statusCode, $headers);
    }
}
