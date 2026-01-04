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

namespace Valkyrja\Http\Struct\Request\Trait;

use Valkyrja\Http\Message\Request\Contract\JsonServerRequestContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Struct\Throwable\Exception\InvalidArgumentException;

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

        return $request->onlyParsedJson(...$values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(
        JsonServerRequestContract|ServerRequestContract $request,
        string|int ...$values
    ): array {
        static::ensureJsonRequest($request);

        return $request->exceptParsedJson(...$values);
    }

    /**
     * Ensure the request is a JsonRequest.
     *
     * @param JsonServerRequestContract|ServerRequestContract $request The request
     *
     *
     * @psalm-assert JsonServerRequestContract                $request
     */
    protected static function ensureJsonRequest(JsonServerRequestContract|ServerRequestContract $request): void
    {
        if (! is_a($request, JsonServerRequestContract::class)) {
            throw new InvalidArgumentException('JsonServerRequest is required for this to work.');
        }
    }
}
