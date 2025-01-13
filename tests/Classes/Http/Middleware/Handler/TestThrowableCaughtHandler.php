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

namespace Valkyrja\Tests\Classes\Http\Middleware\Handler;

use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequest;
use Valkyrja\Http\Message\Response\Contract\Response;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;

/**
 * Class TestThrowableCaughtHandler.
 *
 * @author Melech Mizrachi
 */
class TestThrowableCaughtHandler extends ThrowableCaughtHandler
{
    protected int $count = 0;

    /**
     * Get the count of calls.
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @inheritDoc
     */
    public function throwableCaught(ServerRequest $request, Response $response, Throwable $exception): Response
    {
        $this->count++;

        return parent::throwableCaught($request, $response, $exception);
    }
}
