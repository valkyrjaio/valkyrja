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
use Throwable;
use Valkyrja\Http\Server\Handler\RequestHandler;

/**
 * Class SessionCloseRequestHandler.
 *
 * @author Melech Mizrachi
 */
class SessionCloseRequestHandlerClass extends RequestHandler
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
