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

namespace Valkyrja\Tests\Unit\Type\Enums;

use Error;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Enums\UuidVersion;

use function json_encode;

class UuidVersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(7, UuidVersion::cases());
    }

    public function testV1(): void
    {
        self::assertSame('V1', UuidVersion::V1->name);
        self::assertSame(1, UuidVersion::V1->value);
        self::assertSame(json_encode(UuidVersion::V1->value), json_encode(UuidVersion::V1));
    }

    public function testV2(): void
    {
        $this->expectException(Error::class);
        $this->expectExceptionMessage('Undefined constant ' . UuidVersion::class . '::V2');

        // Ensure we do not define V2
        UuidVersion::V2;
    }

    public function testV3(): void
    {
        self::assertSame('V3', UuidVersion::V3->name);
        self::assertSame(3, UuidVersion::V3->value);
        self::assertSame(json_encode(UuidVersion::V3->value), json_encode(UuidVersion::V3));
    }

    public function testV4(): void
    {
        self::assertSame('V4', UuidVersion::V4->name);
        self::assertSame(4, UuidVersion::V4->value);
        self::assertSame(json_encode(UuidVersion::V4->value), json_encode(UuidVersion::V4));
    }

    public function testV5(): void
    {
        self::assertSame('V5', UuidVersion::V5->name);
        self::assertSame(5, UuidVersion::V5->value);
        self::assertSame(json_encode(UuidVersion::V5->value), json_encode(UuidVersion::V5));
    }

    public function testV6(): void
    {
        self::assertSame('V6', UuidVersion::V6->name);
        self::assertSame(6, UuidVersion::V6->value);
        self::assertSame(json_encode(UuidVersion::V6->value), json_encode(UuidVersion::V6));
    }

    public function testV7(): void
    {
        self::assertSame('V7', UuidVersion::V7->name);
        self::assertSame(7, UuidVersion::V7->value);
        self::assertSame(json_encode(UuidVersion::V7->value), json_encode(UuidVersion::V7));
    }

    public function testV8(): void
    {
        self::assertSame('V8', UuidVersion::V8->name);
        self::assertSame(8, UuidVersion::V8->value);
        self::assertSame(json_encode(UuidVersion::V8->value), json_encode(UuidVersion::V8));
    }
}
