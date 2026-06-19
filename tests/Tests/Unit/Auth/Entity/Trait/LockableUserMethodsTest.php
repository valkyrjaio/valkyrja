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

namespace Valkyrja\Tests\Unit\Auth\Entity\Trait;

use Valkyrja\Auth\Constant\UserField;
use Valkyrja\Auth\Entity\Trait\LockableUserMethods;
use Valkyrja\Tests\Unit\Abstract\TestCase;

final class LockableUserMethodsTest extends TestCase
{
    private function user(): object
    {
        return new class {
            use LockableUserMethods;
        };
    }

    public function testGetMaxLoginAttempts(): void
    {
        self::assertSame(3, $this->user()::getMaxLoginAttempts());
    }

    public function testGetLoginAttemptsField(): void
    {
        self::assertSame(UserField::LOGIN_ATTEMPTS, $this->user()::getLoginAttemptsField());
    }

    public function testGetIsLockedField(): void
    {
        self::assertSame(UserField::IS_LOCKED, $this->user()::getIsLockedField());
    }
}