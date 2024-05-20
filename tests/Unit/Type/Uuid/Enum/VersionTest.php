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

namespace Valkyrja\Tests\Unit\Type\Uuid\Enum;

use Error;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Uuid\Enum\Version;

use function json_encode;

class VersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(7, Version::cases());
    }

    public function testV1(): void
    {
        self::assertSame('V1', Version::V1->name);
        self::assertSame(1, Version::V1->value);
        self::assertSame(json_encode(Version::V1->value), json_encode(Version::V1));
    }

    public function testV2(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Undefined constant ' . Version::class . '::V2');

        // Ensure we do not define V2
        Version::V2;
    }

    public function testV3(): void
    {
        self::assertSame('V3', Version::V3->name);
        self::assertSame(3, Version::V3->value);
        self::assertSame(json_encode(Version::V3->value), json_encode(Version::V3));
    }

    public function testV4(): void
    {
        self::assertSame('V4', Version::V4->name);
        self::assertSame(4, Version::V4->value);
        self::assertSame(json_encode(Version::V4->value), json_encode(Version::V4));
    }

    public function testV5(): void
    {
        self::assertSame('V5', Version::V5->name);
        self::assertSame(5, Version::V5->value);
        self::assertSame(json_encode(Version::V5->value), json_encode(Version::V5));
    }

    public function testV6(): void
    {
        self::assertSame('V6', Version::V6->name);
        self::assertSame(6, Version::V6->value);
        self::assertSame(json_encode(Version::V6->value), json_encode(Version::V6));
    }

    public function testV7(): void
    {
        self::assertSame('V7', Version::V7->name);
        self::assertSame(7, Version::V7->value);
        self::assertSame(json_encode(Version::V7->value), json_encode(Version::V7));
    }

    public function testV8(): void
    {
        self::assertSame('V8', Version::V8->name);
        self::assertSame(8, Version::V8->value);
        self::assertSame(json_encode(Version::V8->value), json_encode(Version::V8));
    }
}
