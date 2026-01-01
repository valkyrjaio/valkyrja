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

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;

/**
 * Trait QueryRequestStruct.
 */
trait QueryRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->onlyQueryParams(...$values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(ServerRequestContract $request, string|int ...$values): array
    {
        return $request->exceptQueryParams(...$values);
    }
}
