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

namespace Valkyrja\Tests\Unit\Support\Type;

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Exceptions\InvalidVlidV1Exception;
use Valkyrja\Type\VlidV1;
use Valkyrja\Type\VlidV2;
use Valkyrja\Type\VlidV3;
use Valkyrja\Type\VlidV4;

class VlidV1Test extends AbstractVlidTest
{
    protected const VERSION = VlidVersion::V1;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, VlidV1::VERSION);
    }

    public function testGenerate(): void
    {
        $this->assertTrue(VlidV1::isValid($vlid = VlidV1::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    public function testLowercase(): void
    {
        $this->assertTrue(VlidV1::isValid($lvlid = VlidV1::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV1Exception::class);
        $this->expectExceptionMessage("Invalid VLID V1 $vlid provided.");

        VlidV1::validate($vlid);
    }

    public function testNotValidForOtherVersions(): void
    {
        $this->assertFalse(VlidV1::isValid(VlidV2::generate()));
        $this->assertFalse(VlidV1::isValid(VlidV2::generateLowerCase()));
        $this->assertFalse(VlidV1::isValid(VlidV3::generate()));
        $this->assertFalse(VlidV1::isValid(VlidV3::generateLowerCase()));
        $this->assertFalse(VlidV1::isValid(VlidV4::generate()));
        $this->assertFalse(VlidV1::isValid(VlidV4::generateLowerCase()));
    }
}
