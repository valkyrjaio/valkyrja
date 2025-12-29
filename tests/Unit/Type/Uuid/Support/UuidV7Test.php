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

namespace Valkyrja\Tests\Unit\Type\Uuid\Support;

use Exception;
use Valkyrja\Type\Uuid\Enum\Version;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV7;
use Valkyrja\Type\Uuid\Throwable\Exception\InvalidUuidV7Exception;

class UuidV7Test extends AbstractUuidTestCase
{
    protected const Version VERSION = Version::V7;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV7::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherTypes(): void
    {
        self::assertFalse(UuidV7::isValid(Uuid::v1()));
        self::assertFalse(UuidV7::isValid(Uuid::v3(Uuid::v1(), 'test')));
        self::assertFalse(UuidV7::isValid(Uuid::v4()));
        self::assertFalse(UuidV7::isValid(Uuid::v5(Uuid::v1(), 'test')));
        self::assertFalse(UuidV7::isValid(Uuid::v6()));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidV7Exception::class);
        $this->expectExceptionMessage("Invalid UUID V7 $uuid provided.");

        UuidV7::validate($uuid);
    }
}
