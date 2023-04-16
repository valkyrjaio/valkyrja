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

namespace Valkyrja\Routing\Messages;

use RuntimeException;
use Valkyrja\Http\JsonRequest;
use Valkyrja\Http\Request;

/**
 * Trait JsonParamsMessage.
 *
 * @author Melech Mizrachi
 */
trait JsonParamsMessage
{
    use Message;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(JsonRequest|Request $request, int|string ...$values): array
    {
        static::ensureJsonRequest($request);

        return $request->onlyQueryParams($values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(JsonRequest|Request $request, int|string ...$values): array
    {
        static::ensureJsonRequest($request);

        return $request->exceptQueryParams($values);
    }

    /**
     * Ensure the request is a JsonRequest.
     *
     * @param JsonRequest|Request $request The request
     *
     * @return void
     */
    protected static function ensureJsonRequest(JsonRequest|Request $request): void
    {
        if (! is_a($request, JsonRequest::class)) {
            throw new RuntimeException('Json Request is required for this to work.');
        }
    }
}
