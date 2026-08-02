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
 * Class FastCgiRequestHandler.
 */
final class FastCgiRequestHandlerFixture extends RequestHandler
{
    protected bool $hasRequestBeenFinishedWithFastCgi = false;

    public function hasRequestBeenFinishedWithFastCgi(): bool
    {
        return $this->hasRequestBeenFinishedWithFastCgi;
    }

    #[Override]
    protected function shouldUseFastcgiToFinishRequest(): bool
    {
        return true;
    }

    #[Override]
    protected function finishRequestWithFastcgi(): void
    {
        $this->hasRequestBeenFinishedWithFastCgi = true;

        try {
            parent::finishRequestWithFastcgi();
        } catch (Throwable) {
        }
    }
}
