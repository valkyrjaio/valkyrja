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

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\SendingResponseMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\SendingResponseHandlerContract;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestSendingResponseMiddleware.
 */
final class SendingResponseMiddlewareClass implements SendingResponseMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function sendingResponse(ServerRequestContract $request, ResponseContract $response, SendingResponseHandlerContract $handler): ResponseContract
    {
        $this->updateCounter();

        return $handler->sendingResponse($request, $response);
    }
}
