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

namespace Valkyrja\Http\Middleware\Handler;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;

/**
 * Class SendingResponseHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<SendingResponseMiddleware>
 */
class SendingResponseHandler extends Handler implements Contract\SendingResponseHandler
{
    /**
     * @inheritDoc
     */
    public function sendingResponse(ServerRequest $request, Response $response): Response
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->sendingResponse($request, $response, $this)
            : $response;
    }
}
