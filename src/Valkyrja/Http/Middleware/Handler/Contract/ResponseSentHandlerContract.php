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
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;

/**
 * @extends HandlerContract<ResponseSentMiddlewareContract>
 */
interface ResponseSentHandlerContract extends HandlerContract
{
    /**
     * Middleware handler ran after a response has been sent.
     */
    public function responseSent(ServerRequestContract $request, ResponseContract $response): void;
}
