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
use Valkyrja\Type\Exceptions\InvalidUuidV5Exception;
use Valkyrja\Type\Support\Uuid;
use Valkyrja\Type\Support\UuidV5;

class UuidV5Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V5;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV5::VERSION);
    }

    public function test(): void
    {
        $this->assertTrue(UuidV5::isValid($uuid = UuidV5::generate(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV5::isValid(Uuid::v1()));
        $this->assertFalse(UuidV5::isValid(Uuid::v3(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV5::isValid(Uuid::v4()));
        $this->assertFalse(UuidV5::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV5Exception::class);
        $this->expectExceptionMessage("Invalid UUID V5 $uuid provided.");

        UuidV5::validate($uuid);
    }
}
