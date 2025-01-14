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
use Valkyrja\Http\Middleware\Contract\RequestReceivedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\RequestReceivedHandler;
use Valkyrja\Tests\Classes\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestRequestReceivedMiddleware.
 *
 * @author Melech Mizrachi
 */
class RequestReceivedMiddlewareClass implements RequestReceivedMiddleware
{
    use MiddlewareCounterTrait;

    public function requestReceived(ServerRequest $request, RequestReceivedHandler $handler): ServerRequest|Response
    {
        $this->updateCounter();

        return $handler->requestReceived($request);
    }
}
