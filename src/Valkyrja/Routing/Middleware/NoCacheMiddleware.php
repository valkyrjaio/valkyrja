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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

/**
 * Class NoCacheMiddleware.
 *
 * @author Melech Mizrachi
 */
class NoCacheMiddleware extends Middleware
{
    /**
     * @inheritDoc
     */
    public static function after(ServerRequest $request, Response $response): Response
    {
        return $response
            ->withHeader('Expires', 'Sun, 01 Jan 2014 00:00:00 GMT')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache');
    }
}
