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

namespace Valkyrja\Tests\Unit\Type;

use Valkyrja\Type\Enums\UuidVersion;
use Valkyrja\Type\Exceptions\InvalidUuidV3Exception;
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV3;

class UuidV3Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V3;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV3::VERSION);
    }

    public function test(): void
    {
        self::assertTrue(UuidV3::isValid($uuid = UuidV3::generate(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV3::isValid(Uuid::v1()));
        self::assertFalse(UuidV3::isValid(Uuid::v4()));
        self::assertFalse(UuidV3::isValid(Uuid::v5(Uuid::v1(), 'test')));
        self::assertFalse(UuidV3::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV3Exception::class);
        $this->expectExceptionMessage("Invalid UUID V3 $uuid provided.");

        UuidV3::validate($uuid);
    }
}
