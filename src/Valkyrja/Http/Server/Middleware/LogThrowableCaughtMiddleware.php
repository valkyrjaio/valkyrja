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

namespace Valkyrja\Http\Server\Middleware;

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Http\Middleware\Handler\Contract\ThrowableCaughtHandler;
use Valkyrja\Log\Contract\Logger;

/**
 * Class LogExceptionMiddleware.
 *
 * @author Melech Mizrachi
 */
class LogThrowableCaughtMiddleware implements ThrowableCaughtMiddleware
{
    public function __construct(
        protected Logger $logger = new \Valkyrja\Log\Logger(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception, ThrowableCaughtHandler $handler): Response
    {
        $url        = $request->getUri()->getPath();
        $logMessage = "Http Server Error\nUrl: $url";

        $this->logger->exception($exception, $logMessage);

        return $response;
    }
}
