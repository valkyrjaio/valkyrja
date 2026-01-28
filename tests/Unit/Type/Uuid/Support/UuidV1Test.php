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

namespace Valkyrja\Tests\Unit\Type\Uuid\Support;

use Exception;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV1;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV1Exception;

class UuidV1Test extends AbstractUuidTestCase
{
    protected const Version VERSION = Version::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV1::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV1::isValid($uuid = UuidV1::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV1::isValid(Uuid::v3(Uuid::v1(), 'test')));
        self::assertFalse(UuidV1::isValid(Uuid::v4()));
        self::assertFalse(UuidV1::isValid(Uuid::v5(Uuid::v1(), 'test')));
        self::assertFalse(UuidV1::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV1Exception::class);
        $this->expectExceptionMessage("Invalid UUID V1 $uuid provided.");

        UuidV1::validate($uuid);
    }

    /**
     * Test generate with empty string node.
     * When node is empty string, it generates random bytes for the node.
     *
     * @throws Exception
     */
    public function testGenerateWithEmptyStringNode(): void
    {
        // Passing empty string triggers the else branch (lines 85-88)
        // which generates random bytes for the node
        $uuid = UuidV1::generate('');

        self::assertTrue(UuidV1::isValid($uuid));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
    }

    /**
     * Test generate with numeric string node
     * When node is a purely numeric string (all digits), it skips the md5 branch
     * but hits the is_numeric branch which formats the node as hex.
     *
     * @throws Exception
     */
    public function testGenerateWithNumericStringNode(): void
    {
        // A purely numeric string like "123456789012" is valid hex (digits 0-9)
        // so it skips the preg_match branch, but is_numeric returns true
        // triggering the next if branch
        $uuid = UuidV1::generate('123456789012');

        self::assertTrue(UuidV1::isValid($uuid));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
    }
}
