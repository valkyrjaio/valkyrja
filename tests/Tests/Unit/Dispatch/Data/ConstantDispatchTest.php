<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Dispatch\Data;

use JsonException;
use Valkyrja\Dispatch\Data\ConstantDispatch;
use Valkyrja\Dispatch\Throwable\Exception\DispatchNoClassException;
use Valkyrja\Tests\Fixtures\Dispatch\InvalidDispatcherFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ConstantDispatch.
 */
final class ConstantDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testConstant(): void
    {
        $constant  = 'TEST';
        $constant2 = 'TEST2';

        $dispatch = new ConstantDispatch(constant: $constant);

        self::assertSame($constant, $dispatch->getConstant());

        $newDispatch = $dispatch->withConstant($constant2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($constant, $dispatch->getConstant());
        self::assertSame($constant2, $newDispatch->getConstant());
        self::assertSame($constant, $dispatch->__toString());
        self::assertSame($constant2, $newDispatch->__toString());
    }

    /**
     * @throws JsonException
     */
    public function testClass(): void
    {
        $constant = 'TEST';
        $class    = InvalidDispatcherFixture::class;

        $dispatch = new ConstantDispatch(constant: $constant);

        self::assertFalse($dispatch->hasClass());

        $newDispatch = $dispatch->withClass($class);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertFalse($dispatch->hasClass());
        self::assertTrue($newDispatch->hasClass());
        self::assertSame($class, $newDispatch->getClass());
        self::assertSame($constant, $dispatch->__toString());
        self::assertSame("$class::$constant", $newDispatch->__toString());

        $newDispatch2 = $newDispatch->withoutClass();

        self::assertNotSame($newDispatch, $newDispatch2);
        self::assertFalse($newDispatch2->hasClass());
        self::assertTrue($newDispatch->hasClass());
        self::assertSame($class, $newDispatch->getClass());
        self::assertSame($constant, $newDispatch2->__toString());
        self::assertSame("$class::$constant", $newDispatch->__toString());
    }

    public function testClassThrowsWhenNoClassSet(): void
    {
        $this->expectException(DispatchNoClassException::class);
        $this->expectExceptionMessage('No class set');

        $constant = 'TEST';

        $dispatch = new ConstantDispatch(constant: $constant);

        $dispatch->getClass();
    }
}
