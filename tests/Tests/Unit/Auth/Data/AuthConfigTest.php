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

namespace Valkyrja\Tests\Unit\Auth\Data;

use Valkyrja\Auth\Authenticator\SessionAuthenticator;
use Valkyrja\Auth\Constant\SessionItemId;
use Valkyrja\Auth\Data\AuthConfig;
use Valkyrja\Auth\Data\AuthSessionConfig;
use Valkyrja\Auth\Data\Contract\AuthConfigContract;
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Store\NullStore;
use Valkyrja\Auth\Store\OrmStore;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class AuthConfigTest extends TestCase
{
    public function testImplementsContract(): void
    {
        self::assertInstanceOf(AuthConfigContract::class, new AuthConfig());
    }

    public function testDefaults(): void
    {
        $config = new AuthConfig();

        self::assertSame(SessionAuthenticator::class, $config->defaultAuthenticator);
        self::assertSame(OrmStore::class, $config->defaultStore);
        self::assertSame(User::class, $config->defaultUserEntity);
        self::assertSame(SessionItemId::AUTHENTICATED_USERS, $config->session->itemId);
    }

    public function testCustomValuesAreStored(): void
    {
        $session = new AuthSessionConfig(itemId: 'auth.custom');

        $config = new AuthConfig(
            defaultStore: NullStore::class,
            session: $session,
        );

        self::assertSame(NullStore::class, $config->defaultStore);
        self::assertSame($session, $config->session);
    }
}
