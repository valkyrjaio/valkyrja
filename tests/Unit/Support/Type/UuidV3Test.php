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
use Valkyrja\Support\Type\Exceptions\InvalidUuidV3Exception;
use Valkyrja\Support\Type\Uuid;
use Valkyrja\Support\Type\UuidV3;

class UuidV3Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V3;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV3::VERSION);
    }

    public function test(): void
    {
        $this->assertTrue(UuidV3::isValid($uuid = UuidV3::generate(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV3::isValid(Uuid::v1()));
        $this->assertFalse(UuidV3::isValid(Uuid::v4()));
        $this->assertFalse(UuidV3::isValid(Uuid::v5(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV3::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV3Exception::class);
        $this->expectExceptionMessage("Invalid UUID V3 $uuid provided.");

        UuidV3::validate($uuid);
    }
}
