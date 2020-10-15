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
use Valkyrja\Http\Response;
use Valkyrja\Routing\Support\Middleware;

/**
 * Class NoCacheMiddleware.
 *
 * @author Melech Mizrachi
 */
class NoCacheMiddleware extends Middleware
{
    /**
     * Middleware handler for after a request is dispatched.
     *
     * @param Request  $request  The request
     * @param Response $response The response
     *
     * @return Response
     */
    public static function after(Request $request, Response $response): Response
    {
        return $response
            ->withHeader('Expires', 'Sun, 01 Jan 2014 00:00:00 GMT')
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache');
    }
}
