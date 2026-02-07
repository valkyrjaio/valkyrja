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

namespace Valkyrja\Tests\Unit\Auth\Hasher;

use Valkyrja\Auth\Hasher\PhpPasswordHasher;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the PhpPasswordHasher class.
 */
final class PhpPasswordHasherTest extends TestCase
{
    protected const string PASSWORD         = 'SecureP@ssw0rd!';
    protected const string WRONG_PASSWORD   = 'WrongPassword';
    protected const string SPECIAL_PASSWORD = '!@#$%^&*()_+-=[]{}|;:\'",.<>?/`~';

    protected PhpPasswordHasher $hasher;

    protected function setUp(): void
    {
        $this->hasher = new PhpPasswordHasher();
    }

    public function testHashPassword(): void
    {
        $hash = $this->hasher->hashPassword(self::PASSWORD);

        self::assertNotEmpty($hash);
        self::assertNotSame(self::PASSWORD, $hash);
    }

    public function testHashPasswordProducesDifferentHashes(): void
    {
        $hash1 = $this->hasher->hashPassword(self::PASSWORD);
        $hash2 = $this->hasher->hashPassword(self::PASSWORD);

        // Due to salting, same password should produce different hashes
        self::assertNotSame($hash1, $hash2);
    }

    public function testConfirmPasswordWithCorrectPassword(): void
    {
        $hash = $this->hasher->hashPassword(self::PASSWORD);

        self::assertTrue($this->hasher->confirmPassword(self::PASSWORD, $hash));
    }

    public function testConfirmPasswordWithWrongPassword(): void
    {
        $hash = $this->hasher->hashPassword(self::PASSWORD);

        self::assertFalse($this->hasher->confirmPassword(self::WRONG_PASSWORD, $hash));
    }

    public function testHashAndConfirmSpecialCharacters(): void
    {
        $hash = $this->hasher->hashPassword(self::SPECIAL_PASSWORD);

        self::assertTrue($this->hasher->confirmPassword(self::SPECIAL_PASSWORD, $hash));
        self::assertFalse($this->hasher->confirmPassword(self::PASSWORD, $hash));
    }

    public function testHashAndConfirmUnicodePassword(): void
    {
        $unicodePassword = 'P@ssw0rd\u4e2d\u6587\u0410\u0411\u0412';
        $hash            = $this->hasher->hashPassword($unicodePassword);

        self::assertTrue($this->hasher->confirmPassword($unicodePassword, $hash));
    }

    public function testHashAndConfirmLongPassword(): void
    {
        $longPassword = str_repeat('a', 1000);
        $hash         = $this->hasher->hashPassword($longPassword);

        self::assertTrue($this->hasher->confirmPassword($longPassword, $hash));
    }

    public function testConfirmPasswordIsCaseSensitive(): void
    {
        $hash = $this->hasher->hashPassword('Password');

        self::assertFalse($this->hasher->confirmPassword('password', $hash));
        self::assertFalse($this->hasher->confirmPassword('PASSWORD', $hash));
        self::assertTrue($this->hasher->confirmPassword('Password', $hash));
    }
}
