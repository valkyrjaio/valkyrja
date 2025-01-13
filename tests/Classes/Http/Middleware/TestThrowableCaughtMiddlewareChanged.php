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

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;

/**
 * Class TestThrowableCaughtMiddlewareChanged.
 *
 * @author Melech Mizrachi
 */
class TestThrowableCaughtMiddlewareChanged implements ThrowableCaughtMiddleware
{
    use MiddlewareCounter;

    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception, ThrowableCaughtHandler $handler): Response
    {
        $this->updateCounter();

        return new \Valkyrja\Http\Message\Response\Response();
    }
}
