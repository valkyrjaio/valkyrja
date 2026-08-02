<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Middleware\Handler;

use Override;
use Throwable;
use Valkyrja\Http\Message\Request\Contract\ServerRequestContract;
use Valkyrja\Http\Message\Response\Contract\ResponseContract;
use Valkyrja\Http\Middleware\Handler\ThrowableCaughtHandler;

/**
 * Class TestThrowableCaughtHandler.
 */
final class ThrowableCaughtHandlerFixture extends ThrowableCaughtHandler
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
    #[Override]
    public function throwableCaught(ServerRequestContract $request, ResponseContract $response, Throwable $throwable): ResponseContract
    {
        $this->count++;

        return parent::throwableCaught($request, $response, $throwable);
    }
}
