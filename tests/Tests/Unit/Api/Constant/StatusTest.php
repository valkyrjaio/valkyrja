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

namespace Valkyrja\Tests\Unit\Api\Constant;

use ReflectionClass;
use Valkyrja\Api\Constant\Status;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Status constants.
 */
class StatusTest extends TestCase
{
    public function testSuccessConstant(): void
    {
        self::assertSame('success', Status::SUCCESS);
    }

    public function testErrorConstant(): void
    {
        self::assertSame('error', Status::ERROR);
    }

    public function testFailConstant(): void
    {
        self::assertSame('fail', Status::FAIL);
    }

    public function testClassIsFinal(): void
    {
        $reflection = new ReflectionClass(Status::class);

        self::assertTrue($reflection->isFinal());
    }

    public function testAllConstantsAreStrings(): void
    {
        $reflection = new ReflectionClass(Status::class);
        $constants  = $reflection->getConstants();

        foreach ($constants as $name => $value) {
            self::assertIsString($value, "Constant {$name} should be a string");
        }
    }
}
