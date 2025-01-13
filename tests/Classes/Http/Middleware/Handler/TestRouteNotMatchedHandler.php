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

namespace Valkyrja\Tests\Classes\Http\Middleware\Handler;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;

/**
 * Class TestRouteNotMatchedHandler.
 *
 * @author Melech Mizrachi
 */
class TestRouteNotMatchedHandler extends RouteNotMatchedHandler
{
    protected int $count = 0;

    /**
     * Get the count of calls.
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function routeNotMatched(ServerRequest $request, Response $response): Response
    {
        $this->count++;

        return parent::routeNotMatched($request, $response);
    }
}
