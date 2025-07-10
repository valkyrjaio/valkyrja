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

use Override;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;

/**
 * Class RequestReceivedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<RequestReceivedMiddleware>
 */
class RequestReceivedHandler extends Handler implements Contract\RequestReceivedHandler
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function requestReceived(ServerRequest $request): Response|ServerRequest
    {
        $next = $this->next;

        return $next !== null
            ? $this->getMiddleware($next)->requestReceived($request, $this)
            : $request;
    }
}
