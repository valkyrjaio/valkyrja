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

use function strlen;
use function substr;
use function Valkyrja\redirectTo;

/**
 * Class RedirectTrailingSlashMiddleware.
 *
 * @author Melech Mizrachi
 */
class RedirectTrailingSlashMiddleware extends Middleware
{
    /**
     * Middleware handler for before a request is dispatched.
     *
     * @param Request $request The request
     *
     * @return Request|Response
     */
    public static function before(Request $request)
    {
        $path = $request->getUri()->getPath();

        if ($path !== '/' && $path[strlen($path) - 1] === '/') {
            $query = $request->getUri()->getQuery();

            redirectTo(substr($path, 0, -1) . ($query ? '?' . $query : ''));
        }

        return $request;
    }
}
