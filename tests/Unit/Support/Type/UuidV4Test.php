<?php
declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Tests\Unit\Support\Type;

use Valkyrja\Type\Enums\UuidVersion;
use Valkyrja\Type\Exceptions\InvalidUuidV4Exception;
use Valkyrja\Type\Uuid;
use Valkyrja\Type\UuidV4;

class UuidV4Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V4;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV4::VERSION);
    }

    public function test(): void
    {
        $this->assertTrue(UuidV4::isValid($uuid = UuidV4::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV4::isValid(Uuid::v1()));
        $this->assertFalse(UuidV4::isValid(Uuid::v3(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV4::isValid(Uuid::v5(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV4::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV4Exception::class);
        $this->expectExceptionMessage("Invalid UUID V4 $uuid provided.");

        UuidV4::validate($uuid);
    }
}
