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

namespace Valkyrja\Http\Middleware\Handler\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;

/**
 * Interface RequestReceivedHandler.
 *
 * @author Melech Mizrachi
 *
 * @extends Handler<RequestReceivedMiddleware>
 */
interface RequestReceivedHandler extends Handler
{
    /**
     * Middleware handler for a received request.
     */
    public function requestReceived(ServerRequest $request): Response|ServerRequest;
}
