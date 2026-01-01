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

namespace Valkyrja\Session\Manager;

use Valkyrja\Log\Logger\Contract\LoggerContract;
use Valkyrja\Session\Data\CookieParams;

class LogSession extends PhpSession
{
    public function __construct(
        protected LoggerContract $logger,
        CookieParams $cookieParams,
        string|null $sessionId = null,
        string|null $sessionName = null
    ) {
        parent::__construct(
            cookieParams: $cookieParams,
            sessionId: $sessionId,
            sessionName: $sessionName
        );
    }
}
