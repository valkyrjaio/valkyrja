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

namespace Valkyrja\Session\Data;

use Valkyrja\Session\Data\Contract\SessionConfigContract;
use Valkyrja\Session\Manager\Contract\SessionContract;
use Valkyrja\Session\Manager\PhpSession;

class SessionConfig implements SessionConfigContract
{
    /**
     * @param class-string<SessionContract> $defaultSession The session to use by default
     * @param non-empty-string|null         $sessionId      The id to give the session
     * @param non-empty-string|null         $sessionName    The name to give the session
     */
    public function __construct(
        public readonly string $defaultSession = PhpSession::class,
        public readonly string|null $sessionId = null,
        public readonly string|null $sessionName = null,
    ) {
    }
}
