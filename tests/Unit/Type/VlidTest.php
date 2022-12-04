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

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Exceptions\InvalidVlidException;
use Valkyrja\Type\Vlid;
use Valkyrja\Type\VlidV1;
use Valkyrja\Type\VlidV2;
use Valkyrja\Type\VlidV3;
use Valkyrja\Type\VlidV4;

class VlidTest extends AbstractVlidTest
{
    public function testDefaultVersion(): void
    {
        $this->assertSame(VlidVersion::V1, Vlid::VERSION);
    }

    public function testGenerate(): void
    {
        $this->assertTrue(Vlid::isValid($vlid = Vlid::generate()));
        $this->assertTrue(Vlid::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
    }

    public function testGenerateToEnsureDefaultsToV1(): void
    {
        $this->assertTrue(VlidV1::isValid($vlid = Vlid::generate()));
        $this->assertTrue(VlidV1::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
    }

    public function testV1(): void
    {
        $this->assertTrue(VlidV1::isValid($vlid = Vlid::v1()));
        $this->assertTrue(VlidV1::isValid($lvlid = Vlid::v1(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
        $this->assertTrue(Vlid::isValid($vlid));
        $this->assertTrue(Vlid::isValid($lvlid));
    }

    public function testV2(): void
    {
        $this->assertTrue(VlidV2::isValid($vlid = Vlid::v2()));
        $this->assertTrue(VlidV2::isValid($lvlid = Vlid::v2(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V2, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V2, $lvlid);
        $this->assertTrue(Vlid::isValid($vlid));
        $this->assertTrue(Vlid::isValid($lvlid));
    }

    public function testV3(): void
    {
        $this->assertTrue(VlidV3::isValid($vlid = Vlid::v3()));
        $this->assertTrue(VlidV3::isValid($lvlid = Vlid::v3(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V3, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V3, $lvlid);
        $this->assertTrue(Vlid::isValid($vlid));
        $this->assertTrue(Vlid::isValid($lvlid));
    }

    public function testV4(): void
    {
        $this->assertTrue(VlidV4::isValid($vlid = Vlid::v4()));
        $this->assertTrue(VlidV4::isValid($lvlid = Vlid::v4(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V4, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V4, $lvlid);
        $this->assertTrue(Vlid::isValid($vlid));
        $this->assertTrue(Vlid::isValid($lvlid));
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidException::class);
        $this->expectExceptionMessage("Invalid VLID $vlid provided.");

        Vlid::validate($vlid);
    }
}