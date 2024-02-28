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
use Valkyrja\Type\Exceptions\InvalidUuidV6Exception;
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV6;

class UuidV6Test extends AbstractUuidTestCase
{
    protected const VERSION = UuidVersion::V6;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV6::VERSION);
    }

    public function test(): void
    {
        self::assertTrue(UuidV6::isValid($uuid = UuidV6::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV6::isValid(Uuid::v1()));
        self::assertFalse(UuidV6::isValid(Uuid::v3(Uuid::v1(), 'test')));
        self::assertFalse(UuidV6::isValid(Uuid::v4()));
        self::assertFalse(UuidV6::isValid(Uuid::v5(Uuid::v1(), 'test')));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV6Exception::class);
        $this->expectExceptionMessage("Invalid UUID V6 $uuid provided.");

        UuidV6::validate($uuid);
    }
}
