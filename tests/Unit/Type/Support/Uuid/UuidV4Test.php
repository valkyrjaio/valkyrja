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

namespace Valkyrja\Tests\Unit\Type\Support\Uuid;

use Exception;
use Valkyrja\Type\Enums\UuidVersion;
use Valkyrja\Type\Exceptions\InvalidUuidV4Exception;
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV4;

class UuidV4Test extends AbstractUuidTestCase
{
    protected const VERSION = UuidVersion::V4;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV4::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV4::isValid($uuid = UuidV4::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV4::isValid(Uuid::v1()));
        self::assertFalse(UuidV4::isValid(Uuid::v3(Uuid::v1(), 'test')));
        self::assertFalse(UuidV4::isValid(Uuid::v5(Uuid::v1(), 'test')));
        self::assertFalse(UuidV4::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV4Exception::class);
        $this->expectExceptionMessage("Invalid UUID V4 $uuid provided.");

        UuidV4::validate($uuid);
    }
}
