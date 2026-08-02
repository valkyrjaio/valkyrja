<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Server\Middleware\RouteNotMatched;

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
