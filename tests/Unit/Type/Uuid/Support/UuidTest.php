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
use Valkyrja\Type\Uuid\Exception\InvalidUuidException;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV1;
use Valkyrja\Type\Uuid\Support\UuidV3;
use Valkyrja\Type\Uuid\Support\UuidV4;
use Valkyrja\Type\Uuid\Support\UuidV5;
use Valkyrja\Type\Uuid\Support\UuidV6;

class UuidTest extends AbstractUuidTestCase
{
    public function testDefaultVersion(): void
    {
        self::assertSame(Version::V1, Uuid::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testV1(): void
    {
        self::assertTrue(UuidV1::isValid($uuid = Uuid::v1()));
        $this->ensureVersionInGeneratedString(Version::V1, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV3(): void
    {
        self::assertTrue(UuidV3::isValid($uuid = Uuid::v3(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(Version::V3, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV4(): void
    {
        self::assertTrue(UuidV4::isValid($uuid = Uuid::v4()));
        $this->ensureVersionInGeneratedString(Version::V4, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV5(): void
    {
        self::assertTrue(UuidV5::isValid($uuid = Uuid::v5(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(Version::V5, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV6(): void
    {
        self::assertTrue(UuidV6::isValid($uuid = Uuid::v6()));
        $this->ensureVersionInGeneratedString(Version::V6, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage("Invalid UUID $uuid provided.");

        Uuid::validate($uuid);
    }
}
