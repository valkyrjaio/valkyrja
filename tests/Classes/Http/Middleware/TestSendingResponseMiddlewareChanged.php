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

namespace Valkyrja\Tests\Classes\Http\Middleware;

use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandler;

/**
 * Class TestSendingResponseMiddlewareChanged.
 *
 * @author Melech Mizrachi
 */
class TestSendingResponseMiddlewareChanged implements SendingResponseMiddleware
{
    use MiddlewareCounter;

    public function sendingResponse(ServerRequest $request, Response $response, SendingResponseHandler $handler): Response
    {
        $this->updateCounter();

        return new \Valkyrja\Http\Message\Response\Response();
    }
}
