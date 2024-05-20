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
use Valkyrja\Type\Uuid\Exception\InvalidUuidV5Exception;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV5;

class UuidV5Test extends AbstractUuidTestCase
{
    protected const VERSION = Version::V5;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV5::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV5::isValid($uuid = UuidV5::generate(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV5::isValid(Uuid::v1()));
        self::assertFalse(UuidV5::isValid(Uuid::v3(Uuid::v1(), 'test')));
        self::assertFalse(UuidV5::isValid(Uuid::v4()));
        self::assertFalse(UuidV5::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV5Exception::class);
        $this->expectExceptionMessage("Invalid UUID V5 $uuid provided.");

        UuidV5::validate($uuid);
    }
}
