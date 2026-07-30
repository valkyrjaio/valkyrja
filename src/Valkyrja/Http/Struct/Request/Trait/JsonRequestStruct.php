<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Throwable\Exception\HttpStructJsonServerRequestExpectedException;

use function is_a;

trait JsonRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(
        JsonServerRequestContract|ServerRequestContract $request,
        string|int ...$values
    ): array {
        static::ensureJsonRequest($request);

        return $request->getParsedJson()->getOnly(...$values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(
        JsonServerRequestContract|ServerRequestContract $request,
        string|int ...$values
    ): array {
        static::ensureJsonRequest($request);

        return $request->getParsedJson()->getAllExcept(...$values);
    }

    /**
     * Ensure the request is a JsonRequest.
     *
     * @param JsonServerRequestContract|ServerRequestContract $request The request
     *
     * @psalm-assert JsonServerRequestContract $request
     *
     * @phpstan-assert JsonServerRequestContract $request
     */
    protected static function ensureJsonRequest(JsonServerRequestContract|ServerRequestContract $request): void
    {
        if (! is_a($request, JsonServerRequestContract::class)) {
            throw new HttpStructJsonServerRequestExpectedException('JsonServerRequest is required for this to work.');
        }
    }
}
