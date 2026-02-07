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

namespace Valkyrja\Tests\Classes\Session;

use Override;
use Valkyrja\Session\Manager\PhpSession;

/**
 * Test class that simulates session_id() returning false.
 */
final class PhpSessionWithAlreadyActiveClass extends PhpSession
{
    public int $sessionStartCount = 0;

    /**
     * @inheritDoc
     */
    #[Override]
    protected function headersSent(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    protected function sessionStart(): bool
    {
        $this->sessionStartCount++;

        return parent::sessionStart();
    }
}
