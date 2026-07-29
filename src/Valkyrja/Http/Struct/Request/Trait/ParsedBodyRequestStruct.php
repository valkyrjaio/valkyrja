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

use Valkyrja\Http\Message\Param\Contract\ParsedBodyParamCollectionContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

trait ParsedBodyRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     *
     * @return array<non-empty-string|int, string|ParsedBodyParamCollectionContract>
     */
    protected static function getOnlyParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->getParsedBody()->getOnly(...$values);
    }

    /**
     * @inheritDoc
     *
     * @return array<non-empty-string|int, string|ParsedBodyParamCollectionContract>
     */
    protected static function getExceptParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->getParsedBody()->getAllExcept(...$values);
    }
}
