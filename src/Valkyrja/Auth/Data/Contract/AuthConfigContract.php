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

namespace Valkyrja\Auth\Data\Contract;

use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Data\AuthSessionConfig;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Store\Contract\StoreContract;

interface AuthConfigContract
{
    /** @var class-string<AuthenticatorContract> */
    public string $defaultAuthenticator {
        get;
    }

    /** @var class-string<StoreContract> */
    public string $defaultStore {
        get;
    }

    /** @var class-string<UserContract> */
    public string $defaultUserEntity {
        get;
    }

    public AuthSessionConfig $session {
        get;
    }
}
