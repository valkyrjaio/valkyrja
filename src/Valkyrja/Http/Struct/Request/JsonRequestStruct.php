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

namespace Valkyrja\Http\Struct\Request;

use Valkyrja\Http\Message\Request\Contract\JsonServerRequest;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Struct\Throwable\Exception\InvalidArgumentException;

/**
 * Trait JsonRequestStruct.
 *
 * @author Melech Mizrachi
 */
trait JsonRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(
        JsonServerRequest|ServerRequest $request,
        string|int ...$values
    ): array {
        static::ensureJsonRequest($request);

        return $request->onlyParsedJson(...$values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(
        JsonServerRequest|ServerRequest $request,
        string|int ...$values
    ): array {
        static::ensureJsonRequest($request);

        return $request->exceptParsedJson(...$values);
    }

    /**
     * Ensure the request is a JsonRequest.
     *
     * @param JsonServerRequest|ServerRequest $request The request
     *
     * @return void
     *
     * @psalm-assert JsonServerRequest        $request
     */
    protected static function ensureJsonRequest(JsonServerRequest|ServerRequest $request): void
    {
        if (! is_a($request, JsonServerRequest::class)) {
            throw new InvalidArgumentException('JsonServerRequest is required for this to work.');
        }
    }
}
