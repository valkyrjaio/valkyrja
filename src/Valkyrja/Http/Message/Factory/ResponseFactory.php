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
use Valkyrja\Http\Message\Factory\Contract\ResponseFactoryContract;
use Valkyrja\Http\Message\Response\Contract\JsonResponseContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\Contract\TextResponseContract;
use Valkyrja\Http\Message\Response\JsonResponse;
use Valkyrja\Http\Message\Response\RedirectResponse;
use Valkyrja\Http\Message\Response\Response;
use Valkyrja\Http\Message\Response\TextResponse;
use Valkyrja\Http\Message\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Http\Message\Uri\Uri;

class ResponseFactory implements ResponseFactoryContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function createResponse(
        string|null $content = null,
        StatusCode|null $statusCode = null,
        array|null $headers = null
    ): ResponseContract {
        return Response::create(
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
    ): TextResponseContract {
        return TextResponse::create(
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
    ): JsonResponseContract {
        return JsonResponse::createFromData(
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
    ): JsonResponseContract {
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
    ): RedirectResponseContract {
        return RedirectResponse::createFromUri(
            uri: Uri::fromString($uri ?? '/'),
            statusCode: $statusCode,
            headers: $headers
        );
    }
}
