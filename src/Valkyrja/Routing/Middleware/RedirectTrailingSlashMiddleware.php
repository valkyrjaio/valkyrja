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

/**
 * Class RedirectTrailingSlashMiddleware.
 *
 * @author Melech Mizrachi
 */
class RedirectTrailingSlashMiddleware extends Middleware
{
    /**
     * @inheritDoc
     */
    public static function before(Request $request): Request|Response
    {
        $slash = '/';
        $path  = $request->getUri()->getPath();

        if ($path !== $slash && str_ends_with($path, $slash)) {
            $query = $request->getUri()->getQuery();
            $uri   = '/' . trim($path, $slash) . ($query ? '?' . $query : '');
            /** @var ResponseFactory $responseFactory */
            $responseFactory = self::getContainer()->getSingleton(ResponseFactory::class);

            return $responseFactory->createRedirectResponse($uri);
        }

        return $request;
    }
}
