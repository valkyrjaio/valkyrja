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

namespace Valkyrja\Tests\Unit\Type\Object\Support;

use stdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\Support\Cls;
use Valkyrja\Type\Object\Throwable\Exception\InvalidClassPropertyProvidedException;
use Valkyrja\Type\Object\Throwable\Exception\InvalidClassProvidedException;

final class ClsTest extends TestCase
{
    protected string $validProperty = 'test';

    public function testValidateInherits(): void
    {
        $this->expectException(InvalidClassProvidedException::class);

        Cls::validateInherits(self::class, stdClass::class);
    }

    public function testInherits(): void
    {
        self::assertFalse(Cls::inherits(self::class, stdClass::class));
        self::assertTrue(Cls::inherits(self::class, TestCase::class));
    }

    public function testValidateHasProperty(): void
    {
        $this->expectException(InvalidClassPropertyProvidedException::class);

        Cls::validateHasProperty(self::class, 'test');
    }

    public function testHasProperty(): void
    {
        self::assertFalse(Cls::hasProperty(self::class, 'test'));
        self::assertTrue(Cls::hasProperty(self::class, 'validProperty'));
    }

    public function testGetNiceName(): void
    {
        self::assertSame('ValkyrjaTestsUnitTypeObjectSupportClsTest', Cls::getNiceName(self::class));
    }

    public function testName(): void
    {
        self::assertSame('ClsTest', Cls::getName(self::class));
    }
}
