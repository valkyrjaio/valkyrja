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

namespace Valkyrja\Routing\Events;

use Valkyrja\Event\Constant\Event;
use Valkyrja\Http\Request\Contract\ServerRequest;
use Valkyrja\Routing\Route;

/**
 * Class RouteMatched.
 *
 * @author Melech Mizrachi
 */
class RouteMatched implements Event
{
    /**
     * The route.
     *
     * @var Route
     */
    public Route $route;

    /**
     * The request.
     *
     * @var ServerRequest
     */
    public ServerRequest $request;

    /**
     * RouteMatched constructor.
     *
     * @param Route         $route   The route
     * @param ServerRequest $request The request
     */
    public function __construct(Route $route, ServerRequest $request)
    {
        $this->route   = $route;
        $this->request = $request;
    }
}
