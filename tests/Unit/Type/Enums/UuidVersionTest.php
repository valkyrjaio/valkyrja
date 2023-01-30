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
use Valkyrja\Type\Enums\UuidVersion;

class UuidVersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        $this->assertCount(7, UuidVersion::cases());
    }

    public function testV1(): void
    {
        $this->assertSame('V1', UuidVersion::V1->name);
        $this->assertSame(1, UuidVersion::V1->value);
    }

    public function testV2(): void
    {
        $this->expectError();
        $this->expectErrorMessage('Undefined constant ' . UuidVersion::class . '::V2');

        UuidVersion::V2;
    }

    public function testV3(): void
    {
        $this->assertSame('V3', UuidVersion::V3->name);
        $this->assertSame(3, UuidVersion::V3->value);
    }

    public function testV4(): void
    {
        $this->assertSame('V4', UuidVersion::V4->name);
        $this->assertSame(4, UuidVersion::V4->value);
    }

    public function testV5(): void
    {
        $this->assertSame('V5', UuidVersion::V5->name);
        $this->assertSame(5, UuidVersion::V5->value);
    }

    public function testV6(): void
    {
        $this->assertSame('V6', UuidVersion::V6->name);
        $this->assertSame(6, UuidVersion::V6->value);
    }

    public function testV7(): void
    {
        $this->assertSame('V7', UuidVersion::V7->name);
        $this->assertSame(7, UuidVersion::V7->value);
    }

    public function testV8(): void
    {
        $this->assertSame('V8', UuidVersion::V8->name);
        $this->assertSame(8, UuidVersion::V8->value);
    }
}
