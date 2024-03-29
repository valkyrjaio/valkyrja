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

namespace Valkyrja\Routing\Middleware;

use Valkyrja\Http\Request;

/**
 * Class ValidateParsedBodyRequestMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class ValidateQueryParamsRequestMiddleware extends ValidateRequestMiddleware
{
    use ValidateParamRequestTrait;

    /**
     * @inheritDoc
     */
    protected static function getParamFromRequest(Request $request, string $param): mixed
    {
        return $request->getQueryParam($param);
    }
}
