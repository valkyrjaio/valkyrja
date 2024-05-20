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

namespace Valkyrja\Tests\Unit\Type\Vlid\Enum;

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Vlid\Enum\Version;

use function json_encode;

class VersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(4, Version::cases());
    }

    public function testV1(): void
    {
        self::assertSame('V1', Version::V1->name);
        self::assertSame(1, Version::V1->value);
        self::assertSame(json_encode(Version::V1->value), json_encode(Version::V1));
    }

    public function testV2(): void
    {
        self::assertSame('V2', Version::V2->name);
        self::assertSame(2, Version::V2->value);
        self::assertSame(json_encode(Version::V2->value), json_encode(Version::V2));
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
}
