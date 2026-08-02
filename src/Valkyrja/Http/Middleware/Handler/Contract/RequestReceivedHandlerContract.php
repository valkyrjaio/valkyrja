<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddlewareContract;

/**
 * @extends HandlerContract<RequestReceivedMiddlewareContract>
 */
interface RequestReceivedHandlerContract extends HandlerContract
{
    /**
     * Middleware handler for a received request.
     */
    public function requestReceived(ServerRequestContract $request): ResponseContract|ServerRequestContract;
}
