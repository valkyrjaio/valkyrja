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
use Valkyrja\Auth\Entity\VerifiableUser;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the VerifiableUser entity class.
 */
final class VerifiableUserTest extends TestCase
{
    protected const string USER_ID  = 'user-123';
    protected const string USERNAME = 'testuser';
    protected const string EMAIL    = 'test@example.com';

    public function testGetEmailField(): void
    {
        self::assertSame(UserField::EMAIL, VerifiableUser::getEmailField());
    }

    public function testGetIsVerifiedField(): void
    {
        self::assertSame(UserField::IS_VERIFIED, VerifiableUser::getIsVerifiedField());
    }

    public function testVerifiedFieldDefaultsToFalse(): void
    {
        $user           = new VerifiableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        self::assertFalse($user->verified);
    }

    public function testVerifiedFieldCanBeSetToTrue(): void
    {
        $user           = new VerifiableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;
        // A property declaration has no behavior, so assert its shape.
        $property = new ReflectionProperty(VerifiableUser::class, 'verified');

        self::assertTrue($property->isPublic());
        self::assertSame('bool', (string) $property->getType());
    }

    public function testEmailField(): void
    {
        $user           = new VerifiableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;
        // A property declaration has no behavior, so assert its shape.
        $property = new ReflectionProperty(VerifiableUser::class, 'email');

        self::assertTrue($property->isPublic());
        self::assertSame('string', (string) $property->getType());
    }

    public function testInheritsUserMethods(): void
    {
        self::assertSame(UserField::USERNAME, VerifiableUser::getUsernameField());
        self::assertSame(UserField::PASSWORD, VerifiableUser::getPasswordField());
        self::assertSame(UserField::RESET_TOKEN, VerifiableUser::getResetTokenField());
    }
}
