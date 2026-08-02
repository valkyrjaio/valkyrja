<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Http\Server;

use Override;
use Throwable;
use Valkyrja\Http\Server\Handler\RequestHandler;

/**
 * Class SessionCloseRequestHandler.
 */
final class SessionCloseRequestHandlerFixture extends RequestHandler
{
    protected bool $hasSessionBeenClosed = false;

    public function hasSessionBeenClosed(): bool
    {
        return $this->hasSessionBeenClosed;
    }

    #[Override]
    protected function shouldCloseSession(): bool
    {
        return true;
    }

    #[Override]
    protected function closeSession(): void
    {
        $this->hasSessionBeenClosed = true;

        try {
            parent::closeSession();
        } catch (Throwable) {
        }
    }
}
