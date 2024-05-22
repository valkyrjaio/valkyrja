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
use Valkyrja\Container\Contract\Container;
use Valkyrja\Http\JsonResponse;
use Valkyrja\Http\RedirectResponse;
use Valkyrja\Http\Response;
use Valkyrja\Http\ResponseFactory as Contract;
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
     * ResponseBuilder constructor.
     *
     * @param Container $container
     */
    public function __construct(
        protected Container $container
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createResponse(
        string|null $content = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): Response {
        return \Valkyrja\Http\Responses\Response::create(
            content: $content,
            statusCode: $statusCode,
            headers: $headers
        );
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function createJsonResponse(
        array|null $data = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): JsonResponse {
        return \Valkyrja\Http\Responses\JsonResponse::createFromData(
            data: $data,
            statusCode: $statusCode,
            headers: $headers
        );
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidArgumentException
     * @throws JsonException
     */
    public function createJsonpResponse(
        string $callback,
        array|null $data = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): JsonResponse {
        return $this->createJsonResponse($data, $statusCode, $headers)->withCallback($callback);
    }

    /**
     * @inheritDoc
     */
    public function createRedirectResponse(
        string|null $uri = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): RedirectResponse {
        return \Valkyrja\Http\Responses\RedirectResponse::createFromUri(
            uri: $uri,
            statusCode: $statusCode,
            headers: $headers
        );
    }

    /**
     * @inheritDoc
     */
    public function route(
        string $name,
        array|null $data = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): RedirectResponse {
        /** @var Url $url */
        $url = $this->container->getSingleton(Url::class);

        // Get the uri from the router using the route and parameters
        $uri = $url->getUrl($name, $data);

        return $this->createRedirectResponse($uri, $statusCode, $headers);
    }

    /**
     * @inheritDoc
     */
    public function view(
        string $template,
        array|null $data = null,
        int|null $statusCode = null,
        array|null $headers = null
    ): Response {
        /** @var View $view */
        $view = $this->container->getSingleton(View::class);

        $content = $view->createTemplate($template, $data ?? [])->render();

        return $this->createResponse($content, $statusCode, $headers);
    }
}
