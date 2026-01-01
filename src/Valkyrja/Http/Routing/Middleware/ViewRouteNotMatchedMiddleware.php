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

namespace Valkyrja\Http\Routing\Middleware;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandlerContract;
use Valkyrja\View\Renderer\Contract\RendererContract;

class ViewRouteNotMatchedMiddleware implements RouteNotMatchedMiddlewareContract
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected RendererContract $renderer,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response, RouteNotMatchedHandlerContract $handler): ResponseContract
    {
        $statusCode = $response->getStatusCode();

        $view = $this->renderer
            ->render(
                name: "$this->errorsTemplateDir/" . ((string) $statusCode->value),
                variables: [
                    'request'  => $request,
                    'response' => $response,
                ]
            );

        $stream = new Stream();
        $stream->write($view);
        $stream->rewind();

        return $response->withBody($stream);
    }
}
