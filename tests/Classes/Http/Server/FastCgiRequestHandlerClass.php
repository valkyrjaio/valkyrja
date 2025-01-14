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

use Throwable;
use Valkyrja\Http\Server\RequestHandler;

/**
 * Class FastCgiRequestHandler.
 *
 * @author Melech Mizrachi
 */
class FastCgiRequestHandlerClass extends RequestHandler
{
    protected bool $hasRequestBeenFinishedWithFastCgi = false;

    public function hasRequestBeenFinishedWithFastCgi(): bool
    {
        return $this->hasRequestBeenFinishedWithFastCgi;
    }

    protected function shouldUseFastcgiToFinishRequest(): bool
    {
        return true;
    }

    protected function finishRequestWithFastcgi(): void
    {
        $this->hasRequestBeenFinishedWithFastCgi = true;

        try {
            parent::finishRequestWithFastcgi();
        } catch (Throwable) {
        }
    }
}
