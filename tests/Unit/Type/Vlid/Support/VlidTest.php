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

namespace Valkyrja\Tests\Unit\Type\Vlid\Support;

use Exception;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Support\Vlid;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidException;

class VlidTest extends AbstractVlidTestCase
{
    public function testDefaultVersion(): void
    {
        self::assertSame(Version::V1, Vlid::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(Vlid::isValid($vlid = Vlid::generate()));
        self::assertTrue(Vlid::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testGenerateToEnsureDefaultsToV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::generate()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::generateLowerCase()));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
    }

    /**
     * @throws Exception
     */
    public function testV1(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = Vlid::v1()));
        self::assertTrue(VlidV1::isValid($lvlid = Vlid::v1(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V1, $vlid);
        $this->ensureVersionInGeneratedString(Version::V1, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    /**
     * @throws Exception
     */
    public function testV2(): void
    {
        self::assertTrue(VlidV2::isValid($vlid = Vlid::v2()));
        self::assertTrue(VlidV2::isValid($lvlid = Vlid::v2(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V2, $vlid);
        $this->ensureVersionInGeneratedString(Version::V2, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV3(): void
    {
        self::assertTrue(VlidV3::isValid($vlid = Vlid::v3()));
        self::assertTrue(VlidV3::isValid($lvlid = Vlid::v3(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V3, $vlid);
        $this->ensureVersionInGeneratedString(Version::V3, $lvlid);
        self::assertTrue(Vlid::isValid($vlid));
        self::assertTrue(Vlid::isValid($lvlid));
    }

    public function testV4(): void
    {
        self::assertTrue(VlidV4::isValid($vlid = Vlid::v4()));
        self::assertTrue(VlidV4::isValid($lvlid = Vlid::v4(lowerCase: true)));
        $this->ensureVersionInGeneratedString(Version::V4, $vlid);
        $this->ensureVersionInGeneratedString(Version::V4, $lvlid);
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
