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
use Valkyrja\Type\Uuid\Factory\UuidV3Factory;
use Valkyrja\Type\Uuid\Factory\UuidV4Factory;
use Valkyrja\Type\Uuid\Factory\UuidV5Factory;
use Valkyrja\Type\Uuid\Factory\UuidV6Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidException;

final class UuidFactoryTest extends UuidTestCase
{
    public function testDefaultVersion(): void
    {
        self::assertSame(Version::V1, UuidFactory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testV1(): void
    {
        self::assertTrue(UuidV1Factory::isValid($uuid = UuidFactory::v1()));
        $this->ensureVersionInGeneratedString(Version::V1, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV3(): void
    {
        self::assertTrue(UuidV3Factory::isValid($uuid = UuidFactory::v3(UuidFactory::v1(), 'test')));
        $this->ensureVersionInGeneratedString(Version::V3, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV4(): void
    {
        self::assertTrue(UuidV4Factory::isValid($uuid = UuidFactory::v4()));
        $this->ensureVersionInGeneratedString(Version::V4, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV5(): void
    {
        self::assertTrue(UuidV5Factory::isValid($uuid = UuidFactory::v5(UuidFactory::v1(), 'test')));
        $this->ensureVersionInGeneratedString(Version::V5, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testV6(): void
    {
        self::assertTrue(UuidV6Factory::isValid($uuid = UuidFactory::v6()));
        $this->ensureVersionInGeneratedString(Version::V6, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage("Invalid UUID $uuid provided.");

        UuidFactory::validate($uuid);
    }
}
