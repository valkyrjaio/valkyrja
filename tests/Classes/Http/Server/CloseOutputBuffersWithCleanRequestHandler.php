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

/**
 * Class CloseOutputBuffersRequestHandler.
 *
 * @author Melech Mizrachi
 */
class CloseOutputBuffersWithCleanRequestHandler extends CloseOutputBuffersRequestHandler
{
    protected function finishRequest(): void
    {
        $this->closeOutputBuffers(0, false);
    }
}
