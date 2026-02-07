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

namespace Valkyrja\Tests\Unit\Auth\Entity;

use Valkyrja\Auth\Constant\UserField;
use Valkyrja\Auth\Entity\LockableUser;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the LockableUser entity class.
 */
final class LockableUserTest extends TestCase
{
    protected const string USER_ID  = 'user-123';
    protected const string USERNAME = 'testuser';

    public function testGetMaxLoginAttempts(): void
    {
        self::assertSame(3, LockableUser::getMaxLoginAttempts());
    }

    public function testGetLoginAttemptsField(): void
    {
        self::assertSame(UserField::LOGIN_ATTEMPTS, LockableUser::getLoginAttemptsField());
    }

    public function testGetIsLockedField(): void
    {
        self::assertSame(UserField::IS_LOCKED, LockableUser::getIsLockedField());
    }

    public function testLoginAttemptsDefaultsToZero(): void
    {
        $user           = new LockableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        self::assertSame(0, $user->login_attempts);
    }

    public function testLoginAttemptsCanBeIncremented(): void
    {
        $user           = new LockableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        $user->login_attempts = 1;
        self::assertSame(1, $user->login_attempts);

        $user->login_attempts = 2;
        self::assertSame(2, $user->login_attempts);
    }

    public function testLockedFieldDefaultsToFalse(): void
    {
        $user           = new LockableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        self::assertFalse($user->locked);
    }

    public function testLockedFieldCanBeSetToTrue(): void
    {
        $user           = new LockableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;
        $user->locked   = true;

        self::assertTrue($user->locked);
    }

    public function testInheritsUserMethods(): void
    {
        self::assertSame(UserField::USERNAME, LockableUser::getUsernameField());
        self::assertSame(UserField::PASSWORD, LockableUser::getPasswordField());
        self::assertSame(UserField::RESET_TOKEN, LockableUser::getResetTokenField());
    }
}
