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
use Valkyrja\Type\Exceptions\InvalidUuidV1Exception;
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV1;

class UuidV1Test extends AbstractUuidTestCase
{
    protected const VERSION = UuidVersion::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV1::VERSION);
    }

    public function test(): void
    {
        self::assertTrue(UuidV1::isValid($uuid = UuidV1::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

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
}
