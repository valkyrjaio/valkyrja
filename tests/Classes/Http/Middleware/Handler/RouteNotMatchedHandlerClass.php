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

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\RouteNotMatchedHandler;

/**
 * Class TestRouteNotMatchedHandler.
 *
 * @author Melech Mizrachi
 */
class RouteNotMatchedHandlerClass extends RouteNotMatchedHandler
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
    #[Override]
    public function routeNotMatched(ServerRequestContract $request, ResponseContract $response): ResponseContract
    {
        $this->count++;

        return parent::routeNotMatched($request, $response);
    }
}
