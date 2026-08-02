<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Middleware\Handler;

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Abstract\Handler;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;

/**
 * @extends Handler<ResponseSentMiddlewareContract>
 */
class ResponseSentHandler extends Handler implements ResponseSentHandlerContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function responseSent(ServerRequestContract $request, ResponseContract $response): void
    {
        $next = $this->next;

        if ($next !== null) {
            $this->getMiddleware($next)->responseSent($request, $response, $this);
        }
    }
}
