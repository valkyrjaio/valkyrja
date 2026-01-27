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
class PhpSessionWithFailingGetIdClass extends PhpSession
{
    /**
     * @inheritDoc
     */
    #[Override]
    protected function sessionId(string|null $sessionId = null): string|false
    {
        return false;
    }
}
