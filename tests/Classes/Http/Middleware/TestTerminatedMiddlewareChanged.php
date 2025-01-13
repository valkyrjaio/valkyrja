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
use Valkyrja\Http\Middleware\Contract\TerminatedMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\TerminatedHandler;

/**
 * Class TestTerminatedMiddlewareChanged.
 *
 * @author Melech Mizrachi
 */
class TestTerminatedMiddlewareChanged implements TerminatedMiddleware
{
    use MiddlewareCounter;

    public function terminated(ServerRequest $request, Response $response, TerminatedHandler $handler): void
    {
        $this->updateCounter();
    }
}
