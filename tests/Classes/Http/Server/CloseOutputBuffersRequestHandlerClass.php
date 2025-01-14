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

namespace Valkyrja\Tests\Classes\Http\Server;

use Valkyrja\Http\Server\RequestHandler;

use function ob_start;

/**
 * Class CloseOutputBuffersRequestHandler.
 *
 * @author Melech Mizrachi
 */
class CloseOutputBuffersRequestHandlerClass extends RequestHandler
{
    protected bool $hasRequestBeenFinishedByClosingOutputBuffers = false;

    public function hasRequestBeenFinishedByClosingOutputBuffers(): bool
    {
        return $this->hasRequestBeenFinishedByClosingOutputBuffers;
    }

    protected function shouldCloseOutputBuffersToFinishRequest(): bool
    {
        return true;
    }

    protected function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $this->hasRequestBeenFinishedByClosingOutputBuffers = true;

        parent::closeOutputBuffers($targetLevel, $flush);
    }

    protected function closeOutputBuffersWithFlush(): void
    {
        // Start an output buffer to ensure that only this new one is closed, not one that is opened outside this test
        ob_start();

        parent::closeOutputBuffersWithFlush();
    }

    protected function closeOutputBuffersWithClean(): void
    {
        // Start an output buffer to ensure that only this new one is closed, not one that is opened outside this test
        ob_start();

        parent::closeOutputBuffersWithClean();
    }
}
