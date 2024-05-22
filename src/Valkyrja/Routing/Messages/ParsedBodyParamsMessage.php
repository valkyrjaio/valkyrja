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

use Valkyrja\Http\Request\Contract\ServerRequest;

/**
 * Trait ParsedBodyParamsMessage.
 *
 * @author Melech Mizrachi
 */
trait ParsedBodyParamsMessage
{
    use Message;

    /**
     * @inheritDoc
     */
    protected static function getOnlyParamsFromRequest(ServerRequest $request, int|string ...$values): array
    {
        return $request->onlyParsedBody($values);
    }

    /**
     * @inheritDoc
     */
    protected static function getExceptParamsFromRequest(ServerRequest $request, int|string ...$values): array
    {
        return $request->exceptParsedBody($values);
    }
}
