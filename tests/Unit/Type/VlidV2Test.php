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
use Valkyrja\Type\Exceptions\InvalidVlidV2Exception;
use Valkyrja\Type\Support\VlidV1;
use Valkyrja\Type\Support\VlidV2;
use Valkyrja\Type\Support\VlidV3;
use Valkyrja\Type\Support\VlidV4;

class VlidV2Test extends AbstractVlidTest
{
    protected const VERSION = VlidVersion::V2;

    public function testDefaultVersion(): void
    {
        $this->assertSame(self::VERSION, VlidV2::VERSION);
    }

    public function testGenerate(): void
    {
        $this->assertTrue(VlidV2::isValid($vlid = VlidV2::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    public function testLowercase(): void
    {
        $this->assertTrue(VlidV2::isValid($lvlid = VlidV2::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV2Exception::class);
        $this->expectExceptionMessage("Invalid VLID V2 $vlid provided.");

        VlidV2::validate($vlid);
    }

    public function testNotValidForOtherVersions(): void
    {
        $this->assertFalse(VlidV2::isValid(VlidV1::generate()));
        $this->assertFalse(VlidV2::isValid(VlidV1::generateLowerCase()));
        $this->assertFalse(VlidV2::isValid(VlidV3::generate()));
        $this->assertFalse(VlidV2::isValid(VlidV3::generateLowerCase()));
        $this->assertFalse(VlidV2::isValid(VlidV4::generate()));
        $this->assertFalse(VlidV2::isValid(VlidV4::generateLowerCase()));
    }
}
