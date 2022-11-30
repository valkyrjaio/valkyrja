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
use Valkyrja\Support\Type\Exceptions\InvalidUuidV7Exception;
use Valkyrja\Support\Type\Uuid;
use Valkyrja\Support\Type\UuidV7;

class UuidV7Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V7;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV7::VERSION);
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV7::isValid(Uuid::v1()));
        $this->assertFalse(UuidV7::isValid(Uuid::v3(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV7::isValid(Uuid::v4()));
        $this->assertFalse(UuidV7::isValid(Uuid::v5(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV7::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV7Exception::class);
        $this->expectExceptionMessage("Invalid UUID V7 $uuid provided.");

        UuidV7::validate($uuid);
    }
}
