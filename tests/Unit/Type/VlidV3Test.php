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
use Valkyrja\Type\Exceptions\InvalidVlidV3Exception;
use Valkyrja\Type\Support\VlidV1;
use Valkyrja\Type\Support\VlidV2;
use Valkyrja\Type\Support\VlidV3;
use Valkyrja\Type\Support\VlidV4;

class VlidV3Test extends AbstractVlidTest
{
    protected const VERSION = VlidVersion::V3;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV3::VERSION);
    }

    public function testGenerate(): void
    {
        self::assertTrue(VlidV3::isValid($vlid = VlidV3::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    public function testLowercase(): void
    {
        self::assertTrue(VlidV3::isValid($lvlid = VlidV3::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV3Exception::class);
        $this->expectExceptionMessage("Invalid VLID V3 $vlid provided.");

        VlidV3::validate($vlid);
    }

    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV3::isValid(VlidV1::generate()));
        self::assertFalse(VlidV3::isValid(VlidV1::generateLowerCase()));
        self::assertFalse(VlidV3::isValid(VlidV2::generate()));
        self::assertFalse(VlidV3::isValid(VlidV2::generateLowerCase()));
        self::assertFalse(VlidV3::isValid(VlidV4::generate()));
        self::assertFalse(VlidV3::isValid(VlidV4::generateLowerCase()));
    }
}
