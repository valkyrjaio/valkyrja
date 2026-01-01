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
use Valkyrja\Http\Middleware\Handler\RouteMatchedHandler;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Class TestRouteMatchedHandler.
 *
 * @author Melech Mizrachi
 */
class RouteMatchedHandlerClass extends RouteMatchedHandler
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
    public function routeMatched(ServerRequestContract $request, RouteContract $route): RouteContract|ResponseContract
    {
        $this->count++;

        return parent::routeMatched($request, $route);
    }
}
