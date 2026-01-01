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
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\RedirectResponseContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Response\RedirectResponse as HttpRedirectResponse;
use Valkyrja\Http\Message\Uri\Contract\UriContract;
use Valkyrja\Http\Message\Uri\Uri as HttpUri;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandlerContract;

use function trim;

class RedirectTrailingSlashMiddleware implements RequestReceivedMiddlewareContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequestContract $request, RequestReceivedHandlerContract $handler): ServerRequestContract|ResponseContract
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
     * @param ServerRequestContract $request
     *
     * @return bool
     */
    protected function shouldRedirectRequest(ServerRequestContract $request): bool
    {
        $pathSeparator = '/';
        // Get the request path
        $path = $request->getUri()->getPath();

        return $path !== $pathSeparator && str_ends_with($path, $pathSeparator);
    }

    /**
     * Create a Uri to redirect to.
     *
     * @param UriContract $uri
     *
     * @return UriContract
     */
    protected function createBeforeRedirectUri(UriContract $uri): UriContract
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
     * @param UriContract $uri
     *
     * @return RedirectResponseContract
     */
    protected function createBeforeRedirectResponse(UriContract $uri): RedirectResponseContract
    {
        return HttpRedirectResponse::createFromUri(uri: $uri);
    }
}
