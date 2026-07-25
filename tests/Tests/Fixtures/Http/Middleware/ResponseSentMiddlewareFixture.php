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

namespace Valkyrja\Tests\Fixtures\Http\Middleware;

use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Contract\ResponseSentMiddlewareContract;
use Valkyrja\Http\Middleware\Handler\Contract\ResponseSentHandlerContract;
use Valkyrja\Tests\Fixtures\Http\Middleware\Trait\MiddlewareCounterTrait;

/**
 * Class TestResponseSentMiddleware.
 */
final class ResponseSentMiddlewareFixture implements ResponseSentMiddlewareContract
{
    use MiddlewareCounterTrait;

    public function responseSent(ServerRequestContract $request, ResponseContract $response, ResponseSentHandlerContract $handler): void
    {
        $this->updateCounter();

        $handler->responseSent($request, $response);
    }
}
