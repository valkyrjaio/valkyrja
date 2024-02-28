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

namespace Valkyrja\Tests\Unit\Type;

use Valkyrja\Type\Enums\VlidVersion;
use Valkyrja\Type\Exceptions\InvalidVlidException;
use Valkyrja\Type\Support\Vlid;
use Valkyrja\Type\Support\VlidV1;
use Valkyrja\Type\Support\VlidV2;
use Valkyrja\Type\Support\VlidV3;
use Valkyrja\Type\Support\VlidV4;

class VlidTest extends AbstractVlidTestCase
{
    public function testDefaultVersion(): void
    {
        self::assertSame(VlidVersion::V1, Vlid::VERSION);
    }

    public function testGenerate(): void
    {
        self::assertTrue(Vlid::isValid($vlid = Vlid::generate()));
        self::assertTrue(Vlid::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
    }

    public function testGenerateToEnsureDefaultsToV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::generate()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
    }

    public function testV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::v1()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::v1(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V1, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV2(): void
    {
        self::assertTrue(VlidV2::isValid($vlid = Vlid::v2()));
        self::assertTrue(VlidV2::isValid($lvlid = Vlid::v2(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V2, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V2, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV3(): void
    {
        self::assertTrue(VlidV3::isValid($vlid = Vlid::v3()));
        self::assertTrue(VlidV3::isValid($lvlid = Vlid::v3(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V3, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V3, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV4(): void
    {
        self::assertTrue(VlidV4::isValid($vlid = Vlid::v4()));
        self::assertTrue(VlidV4::isValid($lvlid = Vlid::v4(lowerCase: true)));
        $this->ensureVersionInGeneratedString(VlidVersion::V4, $vlid);
        $this->ensureVersionInGeneratedString(VlidVersion::V4, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidException::class);
        $this->expectExceptionMessage("Invalid VLID $vlid provided.");

        Vlid::validate($vlid);
    }
}
