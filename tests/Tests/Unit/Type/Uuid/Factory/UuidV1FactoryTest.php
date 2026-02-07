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

namespace Valkyrja\Tests\Unit\Type\Uuid\Factory;

use Exception;
use Valkyrja\Tests\Unit\Type\Uuid\Factory\Abstract\UuidTestCase;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Factory\UuidFactory;
use Valkyrja\Type\Uuid\Factory\UuidV1Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV1Exception;

final class UuidV1FactoryTest extends UuidTestCase
{
    protected const Version VERSION = Version::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV1Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV1Factory::isValid($uuid = UuidV1Factory::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV1Factory::isValid(UuidFactory::v3(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV1Factory::isValid(UuidFactory::v4()));
        self::assertFalse(UuidV1Factory::isValid(UuidFactory::v5(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV1Factory::isValid(UuidFactory::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV1Exception::class);
        $this->expectExceptionMessage("Invalid UUID V1 $uuid provided.");

        UuidV1Factory::validate($uuid);
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
        $uuid = UuidV1Factory::generate('');

        self::assertTrue(UuidV1Factory::isValid($uuid));
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
        $uuid = UuidV1Factory::generate('123456789012');

        self::assertTrue(UuidV1Factory::isValid($uuid));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
    }

    /**
     * Test generate with short hex node that needs zero padding (line 81).
     * When node is a valid hex string less than 12 characters that is not numeric,
     * it gets padded with zeros to reach 12 characters.
     *
     * @throws Exception
     */
    public function testGenerateWithShortHexNodePadsWithZeros(): void
    {
        // 'abcdef' is valid hex (no preg_match), not numeric (skips sprintf),
        // and length 6 < 12, so it hits line 81: $node .= str_repeat('0', 12 - $len)
        $uuid = UuidV1Factory::generate('abcdef');

        self::assertTrue(UuidV1Factory::isValid($uuid));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);

        // The node portion should end with 'abcdef000000' (6 zeros padded)
        self::assertStringEndsWith('abcdef000000', $uuid);
    }
}
