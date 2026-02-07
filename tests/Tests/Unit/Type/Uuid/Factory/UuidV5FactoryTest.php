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
use Valkyrja\Type\Uuid\Factory\UuidV5Factory;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV5Exception;

final class UuidV5FactoryTest extends UuidTestCase
{
    protected const Version VERSION = Version::V5;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV5Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV5Factory::isValid($uuid = UuidV5Factory::generate(UuidFactory::v1(), 'test')));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(UuidFactory::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV5Factory::isValid(UuidFactory::v1()));
        self::assertFalse(UuidV5Factory::isValid(UuidFactory::v3(UuidFactory::v1(), 'test')));
        self::assertFalse(UuidV5Factory::isValid(UuidFactory::v4()));
        self::assertFalse(UuidV5Factory::isValid(UuidFactory::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV5Exception::class);
        $this->expectExceptionMessage("Invalid UUID V5 $uuid provided.");

        UuidV5Factory::validate($uuid);
    }
}
