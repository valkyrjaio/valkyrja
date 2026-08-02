<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
