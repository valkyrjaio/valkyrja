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
        $user->verified = true;

        self::assertTrue($user->verified);
    }

    public function testEmailField(): void
    {
        $user           = new VerifiableUser();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;
        $user->email    = self::EMAIL;

        self::assertSame(self::EMAIL, $user->email);
    }

    public function testInheritsUserMethods(): void
    {
        self::assertSame(UserField::USERNAME, VerifiableUser::getUsernameField());
        self::assertSame(UserField::PASSWORD, VerifiableUser::getPasswordField());
        self::assertSame(UserField::RESET_TOKEN, VerifiableUser::getResetTokenField());
    }
}
