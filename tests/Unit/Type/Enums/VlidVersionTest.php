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

use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Enums\VlidVersion;

use function json_encode;

class VlidVersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        self::assertCount(4, VlidVersion::cases());
    }

    public function testV1(): void
    {
        self::assertSame('V1', VlidVersion::V1->name);
        self::assertSame(1, VlidVersion::V1->value);
        self::assertSame(json_encode(VlidVersion::V1->value), json_encode(VlidVersion::V1));
    }

    public function testV2(): void
    {
        self::assertSame('V2', VlidVersion::V2->name);
        self::assertSame(2, VlidVersion::V2->value);
        self::assertSame(json_encode(VlidVersion::V2->value), json_encode(VlidVersion::V2));
    }

    public function testV3(): void
    {
        self::assertSame('V3', VlidVersion::V3->name);
        self::assertSame(3, VlidVersion::V3->value);
        self::assertSame(json_encode(VlidVersion::V3->value), json_encode(VlidVersion::V3));
    }

    public function testV4(): void
    {
        self::assertSame('V4', VlidVersion::V4->name);
        self::assertSame(4, VlidVersion::V4->value);
        self::assertSame(json_encode(VlidVersion::V4->value), json_encode(VlidVersion::V4));
    }
}
