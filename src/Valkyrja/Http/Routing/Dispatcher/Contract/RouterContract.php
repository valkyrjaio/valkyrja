<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Routing\Dispatcher\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Routing\Data\Contract\RouteContract;

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
