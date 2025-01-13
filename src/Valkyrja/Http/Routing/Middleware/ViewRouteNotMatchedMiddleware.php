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

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Message\Stream\Stream;
use Valkyrja\Http\Middleware\Contract\RouteNotMatchedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RouteNotMatchedHandler;
use Valkyrja\View\Contract\View;

/**
 * Class ViewRouteNotMatchedMiddleware.
 *
 * @author Melech Mizrachi
 */
class ViewRouteNotMatchedMiddleware implements RouteNotMatchedMiddleware
{
    /**
     * The errors template directory.
     *
     * @var string
     */
    protected string $errorsTemplateDir = 'errors';

    public function __construct(
        protected View $view = new \Valkyrja\View\View(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function routeNotMatched(ServerRequest $request, Response $response, RouteNotMatchedHandler $handler): Response
    {
        $statusCode = $response->getStatusCode();

        $view = $this->view
            ->render(
                name: "$this->errorsTemplateDir/" . $statusCode->value,
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
