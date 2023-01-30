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

namespace Valkyrja\Tests\Unit\Type;

use Valkyrja\Type\Enums\UuidVersion;
use Valkyrja\Type\Exceptions\InvalidUuidV8Exception;
use Valkyrja\Type\Uuid;
use Valkyrja\Type\UuidV8;

class UuidV8Test extends AbstractUuidTest
{
    protected const VERSION = UuidVersion::V8;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, UuidV8::VERSION);
    }

    public function testNotValidForOtherTypes(): void
    {
        $this->assertFalse(UuidV8::isValid(Uuid::v1()));
        $this->assertFalse(UuidV8::isValid(Uuid::v3(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV8::isValid(Uuid::v4()));
        $this->assertFalse(UuidV8::isValid(Uuid::v5(Uuid::v1(), 'test')));
        $this->assertFalse(UuidV8::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV8Exception::class);
        $this->expectExceptionMessage("Invalid UUID V8 $uuid provided.");

        UuidV8::validate($uuid);
    }
}
