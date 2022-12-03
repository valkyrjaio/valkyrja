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

namespace Valkyrja\Tests\Unit\Support\Type\Enums;

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Tests\Unit\TestCase;

class VlidVersionTest extends TestCase
{
    public function testTotalCaseCount(): void
    {
        $this->assertCount(4, VlidVersion::cases());
    }

    public function testV1(): void
    {
        $this->assertSame('V1', VlidVersion::V1->name);
        $this->assertSame(1, VlidVersion::V1->value);
    }

    public function testV2(): void
    {
        $this->assertSame('V2', VlidVersion::V2->name);
        $this->assertSame(2, VlidVersion::V2->value);
    }

    public function testV3(): void
    {
        $this->assertSame('V3', VlidVersion::V3->name);
        $this->assertSame(3, VlidVersion::V3->value);
    }

    public function testV4(): void
    {
        $this->assertSame('V4', VlidVersion::V4->name);
        $this->assertSame(4, VlidVersion::V4->value);
    }
}
