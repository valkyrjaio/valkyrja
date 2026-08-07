<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Auth\Entity;

use ReflectionProperty;
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

        // A property declaration has no behavior, so assert its shape.
        $property = new ReflectionProperty(LockableUser::class, 'login_attempts');

        self::assertTrue($property->isPublic());
        self::assertSame('int', (string) $property->getType());
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
        // A property declaration has no behavior, so assert its shape.
        $property = new ReflectionProperty(LockableUser::class, 'locked');

        self::assertTrue($property->isPublic());
        self::assertSame('bool', (string) $property->getType());
    }

    public function testInheritsUserMethods(): void
    {
        self::assertSame(UserField::USERNAME, LockableUser::getUsernameField());
        self::assertSame(UserField::PASSWORD, LockableUser::getPasswordField());
        self::assertSame(UserField::RESET_TOKEN, LockableUser::getResetTokenField());
    }
}
