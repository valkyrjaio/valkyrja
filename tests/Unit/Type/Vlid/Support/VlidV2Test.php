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
use Valkyrja\Type\Vlid\Exception\InvalidVlidV2Exception;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;

class VlidV2Test extends AbstractVlidTestCase
{
    protected const VERSION = Version::V2;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV2::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV2::isValid($vlid = VlidV2::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV2::isValid($lvlid = VlidV2::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV2Exception::class);
        $this->expectExceptionMessage("Invalid VLID V2 $vlid provided.");

        VlidV2::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV2::isValid(VlidV1::generate()));
        self::assertFalse(VlidV2::isValid(VlidV1::generateLowerCase()));
        self::assertFalse(VlidV2::isValid(VlidV3::generate()));
        self::assertFalse(VlidV2::isValid(VlidV3::generateLowerCase()));
        self::assertFalse(VlidV2::isValid(VlidV4::generate()));
        self::assertFalse(VlidV2::isValid(VlidV4::generateLowerCase()));
    }
}
