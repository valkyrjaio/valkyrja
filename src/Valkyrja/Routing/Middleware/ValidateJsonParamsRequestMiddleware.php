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

use RuntimeException;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\JsonServerRequest;

/**
 * Class ValidateParsedBodyRequestMiddleware.
 *
 * @author Melech Mizrachi
 */
abstract class ValidateJsonParamsRequestMiddleware extends ValidateRequestMiddleware
{
    use ValidateParamRequestTrait;

    /**
     * @inheritDoc
     */
    protected static function getParamFromRequest(JsonServerRequest|ServerRequest $request, string $param): mixed
    {
        if (! $request instanceof JsonServerRequest) {
            throw new RuntimeException('Json Request is required for this to work.');
        }

        return $request->getParsedJsonParam($param);
    }
}
