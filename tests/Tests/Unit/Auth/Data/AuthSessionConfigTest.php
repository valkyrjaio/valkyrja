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

use Valkyrja\Auth\Constant\SessionItemId;
use Valkyrja\Auth\Data\AuthenticatedUsers;
use Valkyrja\Auth\Data\AuthSessionConfig;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class AuthSessionConfigTest extends TestCase
{
    public function testDefaults(): void
    {
        $config = new AuthSessionConfig();

        self::assertSame(SessionItemId::AUTHENTICATED_USERS, $config->itemId);
        self::assertSame([AuthenticatedUsers::class], $config->allowedClasses);
    }

    public function testCustomValuesAreStored(): void
    {
        $config = new AuthSessionConfig(
            itemId: 'auth.custom',
            allowedClasses: [],
        );

        self::assertSame('auth.custom', $config->itemId);
        self::assertSame([], $config->allowedClasses);
    }
}
