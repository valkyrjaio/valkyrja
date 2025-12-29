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

namespace Valkyrja\Tests\Unit\Type\Ulid\Support;

use Exception;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\Ulid\Support\Ulid;
use Valkyrja\Type\Ulid\Throwable\Exception\InvalidUlidException;
use Valkyrja\Type\Vlid\Support\VlidV1;
use Valkyrja\Type\Vlid\Support\VlidV2;
use Valkyrja\Type\Vlid\Support\VlidV3;
use Valkyrja\Type\Vlid\Support\VlidV4;

class UlidTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGenerate(): void
    {
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
    }

    /**
     * @throws Exception
     */
    public function testNoStaticPropertyCrossOver(): void
    {
        // Ensure that a generated Ulid is valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV1 and ensure it is valid
        self::assertTrue(VlidV1::isValid(VlidV1::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV2 and ensure it is valid
        self::assertTrue(VlidV2::isValid(VlidV2::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV3 and ensure it is valid
        self::assertTrue(VlidV3::isValid(VlidV3::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
        // Generate a VlidV4 and ensure it is valid
        self::assertTrue(VlidV4::isValid(VlidV4::generate()));
        // Ensure that a generated Ulid is still valid
        self::assertTrue(Ulid::isValid(Ulid::generate()));
        self::assertTrue(Ulid::isValid(Ulid::generateLowerCase()));
    }

    public function testNotValidException(): void
    {
        $ulid = 'test';

        $this->expectException(InvalidUlidException::class);
        $this->expectExceptionMessage("Invalid ULID $ulid provided.");

        Ulid::validate($ulid);
    }
}
