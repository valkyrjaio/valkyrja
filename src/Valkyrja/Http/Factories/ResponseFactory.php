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

namespace Valkyrja\Http\Factories;

use InvalidArgumentException;
use JsonException;
use Valkyrja\Container\Container;
use Valkyrja\Http\Constants\StreamType;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory as Contract;
use Valkyrja\Http\Streams\Stream as HttpStream;
use Valkyrja\Routing\Url;
use Valkyrja\View\View;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * ResponseBuilder constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        $stream = new HttpStream(StreamType::TEMP, 'wb+');
        $stream->write($content ?? '');
        $stream->rewind();

        return new \Valkyrja\Http\Responses\Response($stream, $statusCode, $headers);
    }

    /**
     * Create a JSON response.
     *
     * @param array|null $data       [optional] The data to set
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @throws JsonException
     *
     * @return JsonResponse
     */
    public function createJsonResponse(array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
        return new  \Valkyrja\Http\Responses\JsonResponse($data, $statusCode, $headers);
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
     * @throws JsonException
     *
     * @return JsonResponse
     */
    public function createJsonpResponse(string $callback, array $data = null, int $statusCode = null, array $headers = null): JsonResponse
    {
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
        return new \Valkyrja\Http\Responses\RedirectResponse($uri, $statusCode, $headers);
    }

    /**
     * Redirect to a named route response builder.
     *
     * @param string     $name       The name of the route
     * @param array|null $data       [optional] The data for dynamic routes
     * @param int|null   $statusCode [optional] The response status code
     * @param array|null $headers    [optional] An array of response headers
     *
     * @return RedirectResponse
     */
    public function route(string $name, array $data = null, int $statusCode = null, array $headers = null): RedirectResponse
    {
        /** @var Url $url */
        $url = $this->container->getSingleton(Url::class);

        // Get the uri from the router using the route and parameters
        $uri = $url->getUrl($name, $data);

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
        /** @var View $view */
        $view = $this->container->getSingleton(View::class);

        $content = $view->createTemplate($template, $data ?? [])->render();

        return $this->createResponse($content, $statusCode, $headers);
    }
}
