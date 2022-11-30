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

use Valkyrja\Support\Type\Enums\UuidVersion;
use Valkyrja\Support\Type\Exceptions\InvalidUuidV6Exception;
use Valkyrja\Support\Type\Uuid;
use Valkyrja\Support\Type\UuidV6;

class UuidV6Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V6;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV6::VERSION);
    }

    public function test(): void
    {
        $this->assertTrue(UuidV6::isValid($uuid = UuidV6::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV6::isValid(Uuid::v1()));
        $this->assertFalse(UuidV6::isValid(Uuid::v3(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV6::isValid(Uuid::v4()));
        $this->assertFalse(UuidV6::isValid(Uuid::v5(Uuid::v1(), 'test')));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV6Exception::class);
        $this->expectExceptionMessage("Invalid UUID V6 $uuid provided.");

        UuidV6::validate($uuid);
    }
}
