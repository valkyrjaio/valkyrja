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

namespace Valkyrja\Auth\Data;

use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Data\Contract\AuthConfigContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\OrmStore;

class AuthConfig implements AuthConfigContract
{
    /**
     * @param class-string<AuthenticatorContract> $defaultAuthenticator The authenticator to use by default
     * @param class-string<StoreContract>         $defaultStore         The store to use by default
     * @param class-string<UserContract>          $defaultUserEntity    The user entity to use by default
     */
    public function __construct(
        public readonly string $defaultAuthenticator = SessionAuthenticator::class,
        public readonly string $defaultStore = OrmStore::class,
        public readonly string $defaultUserEntity = User::class,
        public readonly AuthSessionConfig $session = new AuthSessionConfig(),
    ) {
    }
}
