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

use Override;
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
}
