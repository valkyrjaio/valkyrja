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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;

/**
 * Trait ParsedBodyRequestStruct.
 *
 * @author Melech Mizrachi
 */
trait ParsedBodyRequestStruct
{
    use RequestStruct;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(ServerRequest $request, string|int ...$values): array
    {
        return $request->onlyParsedBody(...$values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(ServerRequest $request, string|int ...$values): array
    {
        return $request->exceptParsedBody(...$values);
    }
}
