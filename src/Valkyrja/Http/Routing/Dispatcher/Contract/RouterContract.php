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

namespace Valkyrja\Http\Routing\Dispatcher\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

/**
 * Interface RouterContract.
 *
 * @author Melech Mizrachi
 */
interface RouterContract
{
    /**
     * Dispatch a server request.
     */
    public function dispatch(ServerRequestContract $request): ResponseContract;

    /**
     * Dispatch a server request for a specific route.
     */
    public function dispatchRoute(ServerRequestContract $request, RouteContract $route): ResponseContract;
}
