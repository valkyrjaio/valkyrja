<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Type\Object\Support;

use stdClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;
use Valkyrja\Type\Object\Support\Cls;
use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectPropertyProvidedException;
use Valkyrja\Type\Object\Throwable\Exception\InvalidObjectProvidedException;

final class ClsTest extends TestCase
{
    protected string $validProperty = 'test';

    public function testValidateInherits(): void
    {
        $this->expectException(InvalidObjectProvidedException::class);

        Cls::validateInherits(self::class, stdClass::class);
    }

    public function testValidateInheritsDoesNotThrowForInheritedClass(): void
    {
        Cls::validateInherits(self::class, TestCase::class);

        $this->expectNotToPerformAssertions();
    }

    public function testInherits(): void
    {
        self::assertFalse(Cls::inherits(self::class, stdClass::class));
        self::assertTrue(Cls::inherits(self::class, TestCase::class));
    }

    public function testValidateHasProperty(): void
    {
        $this->expectException(InvalidObjectPropertyProvidedException::class);

        Cls::validateHasProperty(self::class, 'test');
    }

    public function testValidateHasPropertyDoesNotThrowForExistingProperty(): void
    {
        Cls::validateHasProperty(self::class, 'validProperty');

        $this->expectNotToPerformAssertions();
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
