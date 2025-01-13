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

namespace Valkyrja\Http\Middleware\Contract;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;

/**
 * Interface SendingResponseMiddleware.
 *
 * @author Melech Mizrachi
 */
interface SendingResponseMiddleware
{
    /**
     * Middleware handler for before a response has been sent.
     */
    public function sendingResponse(ServerRequest $request, Response $response, SendingResponseHandler $handler): Response;
}
