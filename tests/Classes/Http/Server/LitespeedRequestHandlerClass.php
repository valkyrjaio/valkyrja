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
 * Class LitespeedRequestHandler.
 *
 * @author Melech Mizrachi
 */
class LitespeedRequestHandlerClass extends RequestHandler
{
    protected bool $hasRequestBeenFinishedWithLitespeed = false;

    public function hasRequestBeenFinishedWithLitespeed(): bool
    {
        return $this->hasRequestBeenFinishedWithLitespeed;
    }

    #[Override]
    protected function shouldUseLitespeedToFinishRequest(): bool
    {
        return true;
    }

    #[Override]
    protected function finishRequestWithLitespeed(): void
    {
        $this->hasRequestBeenFinishedWithLitespeed = true;

        try {
            parent::finishRequestWithLitespeed();
        } catch (Throwable) {
        }
    }
}
