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

use Valkyrja\Http\Message\Factory\Contract\ResponseFactory;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;

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
    public static function before(ServerRequest $request): ServerRequest|Response
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
