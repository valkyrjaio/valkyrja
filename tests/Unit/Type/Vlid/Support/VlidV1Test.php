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
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV1Exception;

class VlidV1Test extends AbstractVlidTestCase
{
    protected const Version VERSION = Version::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV1::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV1::isValid($vlid = VlidV1::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV1::isValid($lvlid = VlidV1::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV1Exception::class);
        $this->expectExceptionMessage("Invalid VLID V1 $vlid provided.");

        VlidV1::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV1::isValid(VlidV2::generate()));
        self::assertFalse(VlidV1::isValid(VlidV2::generateLowerCase()));
        self::assertFalse(VlidV1::isValid(VlidV3::generate()));
        self::assertFalse(VlidV1::isValid(VlidV3::generateLowerCase()));
        self::assertFalse(VlidV1::isValid(VlidV4::generate()));
        self::assertFalse(VlidV1::isValid(VlidV4::generateLowerCase()));
    }
}
