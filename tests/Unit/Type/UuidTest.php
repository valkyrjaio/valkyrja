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
use Valkyrja\Type\Exceptions\InvalidUuidException;
use Valkyrja\Type\Uuid;
use Valkyrja\Type\UuidV1;
use Valkyrja\Type\UuidV3;
use Valkyrja\Type\UuidV4;
use Valkyrja\Type\UuidV5;
use Valkyrja\Type\UuidV6;

class UuidTest extends AbstractUuidTest
{
    public function testDefaultVersion(): void
    {
        $this->assertSame(UuidVersion::V1, Uuid::VERSION);
    }

    public function testV1(): void
    {
        $this->assertTrue(UuidV1::isValid($uuid = Uuid::v1()));
        $this->ensureVersionInGeneratedString(UuidVersion::V1, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testV3(): void
    {
        $this->assertTrue(UuidV3::isValid($uuid = Uuid::v3(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(UuidVersion::V3, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testV4(): void
    {
        $this->assertTrue(UuidV4::isValid($uuid = Uuid::v4()));
        $this->ensureVersionInGeneratedString(UuidVersion::V4, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testV5(): void
    {
        $this->assertTrue(UuidV5::isValid($uuid = Uuid::v5(Uuid::v1(), 'test')));
        $this->ensureVersionInGeneratedString(UuidVersion::V5, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testV6(): void
    {
        $this->assertTrue(UuidV6::isValid($uuid = Uuid::v6()));
        $this->ensureVersionInGeneratedString(UuidVersion::V6, $uuid);
        $this->assertTrue(Uuid::isValid($uuid));
    }

    public function testNotValidException(): void
    {
        $uuid = 'test';

        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage("Invalid UUID $uuid provided.");

        Uuid::validate($uuid);
    }
}
