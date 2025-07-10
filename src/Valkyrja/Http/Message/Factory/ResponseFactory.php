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
use Override;
use Valkyrja\Http\Message\Enum\StatusCode;
use Valkyrja\Http\Message\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Factory\Contract\ResponseFactory as Contract;
use Valkyrja\Http\Message\Response\Contract\JsonResponse;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\Contract\TextResponse;
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
    #[Override]
    public function createResponse(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): Response {
        return \Valkyrja\Http\Message\Response\Response::create(
            content: $content,
            statusCode: $statusCode,
            headers: $headers
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createTextResponse(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): TextResponse {
        return \Valkyrja\Http\Message\Response\TextResponse::create(
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
    #[Override]
    public function createJsonResponse(
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
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
    #[Override]
    public function createJsonpResponse(
        string $callback,
        array|null $data = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): JsonResponse {
        return $this->createJsonResponse($data, $statusCode, $headers)->withCallback($callback);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createRedirectResponse(
        string|null $uri = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): RedirectResponse {
        return \Valkyrja\Http\Message\Response\RedirectResponse::createFromUri(
            uri: Uri::fromString($uri ?? '/'),
            statusCode: $statusCode,
            headers: $headers
        );
    }
}
