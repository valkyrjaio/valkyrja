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

use Valkyrja\Http\Message\Param\Contract\QueryParamCollectionContract;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

trait QueryRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     *
     * @return array<non-empty-string|int, string|QueryParamCollectionContract>
     */
    protected static function getOnlyParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->getQueryParams()->getOnly(...$values);
    }

    /**
     * @inheritDoc
     *
     * @return array<non-empty-string|int, string|QueryParamCollectionContract>
     */
    protected static function getExceptParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->getQueryParams()->getAllExcept(...$values);
    }
}
