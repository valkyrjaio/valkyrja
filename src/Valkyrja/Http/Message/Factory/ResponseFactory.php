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

namespace Valkyrja\Http\Message\Factory;

use JsonException;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as Contract;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Uri\Uri;

/**
 * Class ResponseFactory.
 *
 * @author Melech Mizrachi
 */
class ResponseFactory implements Contract
{
    /**
     * @inheritDoc
     */
    public function createResponse(
        ?string $content = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): Response {
        return \Valkyrja\Http\Message\Response\Response::create(
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
        ?array $data = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): JsonResponse {
        return \Valkyrja\Http\Message\Response\JsonResponse::createFromData(
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
        ?array $data = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): JsonResponse {
        return $this->createJsonResponse($data, $statusCode, $headers)->withCallback($callback);
    }

    /**
     * @inheritDoc
     */
    public function createRedirectResponse(
        ?string $uri = null,
        ?StatusCode $statusCode = null,
        ?array $headers = null
    ): RedirectResponse {
        return \Valkyrja\Http\Message\Response\RedirectResponse::createFromUri(
            uri: Uri::fromString($uri ?? '/'),
            statusCode: $statusCode,
            headers: $headers
        );
    }
}
