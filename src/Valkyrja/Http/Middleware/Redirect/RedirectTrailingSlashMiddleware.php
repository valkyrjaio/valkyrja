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

namespace Valkyrja\Http\Middleware\Redirect;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\RedirectResponse;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Response\RedirectResponse as HttpRedirectResponse;
use Valkyrja\Http\Message\Uri\Contract\Uri;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;

use function trim;

/**
 * Class RedirectTrailingSlashMiddleware.
 *
 * @author Melech Mizrachi
 */
class RedirectTrailingSlashMiddleware implements RequestReceivedMiddleware
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequest $request, RequestReceivedHandler $handler): ServerRequest|Response
    {
        if ($this->shouldRedirectRequest($request)) {
            $uri = $this->createBeforeRedirectUri($request->getUri());

            return $this->createBeforeRedirectResponse($uri);
        }

        return $handler->requestReceived($request);
    }

    /**
     * Determine if a request should be redirected.
     *
     * @param ServerRequest $request
     *
     * @return bool
     */
    protected function shouldRedirectRequest(ServerRequest $request): bool
    {
        $pathSeparator = '/';
        // Get the request path
        $path = $request->getUri()->getPath();

        return $path !== $pathSeparator && str_ends_with($path, $pathSeparator);
    }

    /**
     * Create a Uri to redirect to.
     *
     * @param Uri $uri
     *
     * @return Uri
     */
    protected function createBeforeRedirectUri(Uri $uri): Uri
    {
        return new HttpUri(
            path: '/' . trim($uri->getPath(), '/'),
            query: $uri->getQuery(),
            fragment: $uri->getFragment()
        );
    }

    /**
     * Create a RedirectResponse for the before action.
     *
     * @param Uri $uri
     *
     * @return RedirectResponse
     */
    protected function createBeforeRedirectResponse(Uri $uri): RedirectResponse
    {
        return HttpRedirectResponse::createFromUri(uri: $uri);
    }
}
