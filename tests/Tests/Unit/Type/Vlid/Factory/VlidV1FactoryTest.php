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

namespace Valkyrja\Tests\Unit\Type\Vlid\Factory;

use Exception;
use Valkyrja\Tests\Unit\Type\Vlid\Factory\Abstract\VlidTestCase;
use Valkyrja\Type\Vlid\Enum\Version;
use Valkyrja\Type\Vlid\Factory\VlidV1Factory;
use Valkyrja\Type\Vlid\Factory\VlidV2Factory;
use Valkyrja\Type\Vlid\Factory\VlidV3Factory;
use Valkyrja\Type\Vlid\Factory\VlidV4Factory;
use Valkyrja\Type\Vlid\Throwable\Exception\InvalidVlidV1Exception;

class VlidV1FactoryTest extends VlidTestCase
{
    protected const Version VERSION = Version::V1;

    public function testDefaultVersion(): void
    {
        self::assertSame(self::VERSION, VlidV1Factory::VERSION);
    }

    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(VlidV1Factory::isValid($vlid = VlidV1Factory::generate()));
        $this->ensureVersionInGeneratedString(self::VERSION, $vlid);
    }

    /**
     * @throws Exception
     */
    public function testLowercase(): void
    {
        self::assertTrue(VlidV1Factory::isValid($lvlid = VlidV1Factory::generateLowerCase()));
        $this->ensureVersionInGeneratedString(self::VERSION, $lvlid);
    }

    public function testNotValidException(): void
    {
        $vlid = 'test';

        $this->expectException(InvalidVlidV1Exception::class);
        $this->expectExceptionMessage("Invalid VLID V1 $vlid provided.");

        VlidV1Factory::validate($vlid);
    }

    /**
     * @throws Exception
     */
    public function testNotValidForOtherVersions(): void
    {
        self::assertFalse(VlidV1Factory::isValid(VlidV2Factory::generate()));
        self::assertFalse(VlidV1Factory::isValid(VlidV2Factory::generateLowerCase()));
        self::assertFalse(VlidV1Factory::isValid(VlidV3Factory::generate()));
        self::assertFalse(VlidV1Factory::isValid(VlidV3Factory::generateLowerCase()));
        self::assertFalse(VlidV1Factory::isValid(VlidV4Factory::generate()));
        self::assertFalse(VlidV1Factory::isValid(VlidV4Factory::generateLowerCase()));
    }
}
