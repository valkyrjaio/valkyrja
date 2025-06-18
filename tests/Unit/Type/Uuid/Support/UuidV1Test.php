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
use Valkyrja\Type\Uuid\Exception\InvalidUuidV1Exception;
use Valkyrja\Type\Uuid\Support\Uuid;
use Valkyrja\Type\Uuid\Support\UuidV1;

class UuidV1Test extends AbstractUuidTestCase
{
    protected const Version VERSION = Version::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, UuidV1::VERSION);
    }

    /**
     * @throws Exception
     */
    public function test(): void
    {
        self::assertTrue(UuidV1::isValid($uuid = UuidV1::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $uuid);
        self::assertTrue(Uuid::isValid($uuid));
    }

    /**
     * @throws Exception
     */
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
