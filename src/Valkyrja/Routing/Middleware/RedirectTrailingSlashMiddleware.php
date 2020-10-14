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
use Valkyrja\Http\ResponseFactory;
use Valkyrja\Routing\Support\Middleware;

use Valkyrja\Support\Type\Str;

use function substr;

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

        if ($path !== '/' && Str::endsWith($path, '/')) {
            $query = $request->getUri()->getQuery();
            $uri   = substr($path, 0, -1) . ($query ? '?' . $query : '');
            /** @var ResponseFactory $responseFactory */
            $responseFactory = self::$container->getSingleton(ResponseFactory::class);

            return $responseFactory->createRedirectResponse($uri);
        }

        return $request;
    }
}
