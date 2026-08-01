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

namespace Valkyrja\Tests\Fixtures\Auth\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Auth\Authenticator\Contract\AuthenticatorContract;
use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Data\AuthSessionConfig;
use Valkyrja\Auth\Data\Contract\AuthConfigContract;
use Valkyrja\Auth\Entity\Contract\UserContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\Contract\StoreContract;
use Valkyrja\Auth\Store\NullStore;

final class AuthConfigFixture extends Config implements AuthConfigContract
{
    /**
     * @param class-string<AuthenticatorContract> $defaultAuthenticator
     * @param class-string<StoreContract>         $defaultStore
     * @param class-string<UserContract>          $defaultUserEntity
     */
    public function __construct(
        public string $defaultAuthenticator = SessionAuthenticator::class,
        public string $defaultStore = NullStore::class,
        public string $defaultUserEntity = User::class,
        public AuthSessionConfig $session = new AuthSessionConfig(),
    ) {
        parent::__construct();
    }
}
