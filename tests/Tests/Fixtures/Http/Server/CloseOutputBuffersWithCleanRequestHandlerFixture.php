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
use Valkyrja\Http\Server\Handler\RequestHandler;

use function ob_start;

/**
 * Class CloseOutputBuffersRequestHandler.
 */
final class CloseOutputBuffersWithCleanRequestHandlerFixture extends RequestHandler
{
    protected bool $hasRequestBeenFinishedByClosingOutputBuffers = false;

    public function hasRequestBeenFinishedByClosingOutputBuffers(): bool
    {
        return $this->hasRequestBeenFinishedByClosingOutputBuffers;
    }

    #[Override]
    protected function shouldCloseOutputBuffersToFinishRequest(): bool
    {
        return true;
    }

    #[Override]
    protected function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $this->hasRequestBeenFinishedByClosingOutputBuffers = true;

        parent::closeOutputBuffers($targetLevel, $flush);
    }

    #[Override]
    protected function closeOutputBuffersWithFlush(): void
    {
        // Start an output buffer to ensure that only this new one is closed, not one that is opened outside this test
        ob_start();

        parent::closeOutputBuffersWithFlush();
    }

    #[Override]
    protected function closeOutputBuffersWithClean(): void
    {
        // Start an output buffer to ensure that only this new one is closed, not one that is opened outside this test
        ob_start();

        parent::closeOutputBuffersWithClean();
    }

    #[Override]
    protected function finishRequest(): void
    {
        $this->closeOutputBuffers(0, false);
    }
}
