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

use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Http\JsonResponse;
use Valkyrja\Contracts\Http\RedirectResponse;
use Valkyrja\Contracts\Http\Response;
use Valkyrja\Contracts\Http\ResponseBuilder as ResponseBuilderContract;
use Valkyrja\Http\Enums\StatusCode;

/**
 * Class ResponseBuilder.
 *
 * @author Melech Mizrachi
 */
class ResponseBuilder implements ResponseBuilderContract
{
    /**
     * The application.
     *
     * @var Application
     */
    protected $app;

    /**
     * ResponseBuilder constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Make a new instance of Response.
     *
     * @param string $content    [optional] The response content
     * @param int    $statusCode [optional] The response status code
     * @param array  $headers    [optional] An array of response headers
     *
     * @return \Valkyrja\Contracts\Http\Response
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
     * @return \Valkyrja\Contracts\Http\Response
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
     * @return \Valkyrja\Contracts\Http\JsonResponse
     */
    public function json(
        array $data = [],
        int $statusCode = StatusCode::OK,
        array $headers = []
    ): JsonResponse {
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
     * @throws \InvalidArgumentException
     *
     * @return \Valkyrja\Contracts\Http\JsonResponse
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
     * @return \Valkyrja\Contracts\Http\RedirectResponse
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
     * @return \Valkyrja\Contracts\Http\RedirectResponse
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
