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
use Valkyrja\Auth\Entity\User;
use Valkyrja\Auth\Throwable\Exception\RuntimeException;
use Valkyrja\Tests\Unit\Abstract\TestCase;

use const PASSWORD_DEFAULT;

/**
 * Test the User entity class.
 */
final class UserTest extends TestCase
{
    protected const string USER_ID   = 'user-123';
    protected const string USERNAME  = 'testuser';
    protected const string PASSWORD  = 'SecureP@ssw0rd!';

    public function testGetUsernameField(): void
    {
        self::assertSame(UserField::USERNAME, User::getUsernameField());
    }

    public function testGetPasswordField(): void
    {
        self::assertSame(UserField::PASSWORD, User::getPasswordField());
    }

    public function testGetResetTokenField(): void
    {
        self::assertSame(UserField::RESET_TOKEN, User::getResetTokenField());
    }

    public function testGetUsernameValue(): void
    {
        $user           = new User();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        self::assertSame(self::USERNAME, $user->getUsernameValue());
    }

    public function testGetUsernameValueThrowsOnEmptyUsername(): void
    {
        $user     = new User();
        $user->id = self::USER_ID;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Username field value should be a string');

        $user->getUsernameValue();
    }

    public function testGetPasswordValue(): void
    {
        $user           = new User();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;
        $user->password = password_hash(self::PASSWORD, PASSWORD_DEFAULT);

        self::assertNotEmpty($user->getPasswordValue());
    }

    public function testGetPasswordValueThrowsOnEmptyPassword(): void
    {
        $user           = new User();
        $user->id       = self::USER_ID;
        $user->username = self::USERNAME;

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Username field value should be a string');

        $user->getPasswordValue();
    }

    public function testTableName(): void
    {
        self::assertSame('users', User::getTableName());
    }

    public function testIdField(): void
    {
        self::assertSame('id', User::getIdField());
    }
}
