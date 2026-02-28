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

namespace Valkyrja\Tests\Unit\Dispatch\Data;

use JsonException;
use Valkyrja\Dispatch\Data\ConstantDispatch;
use Valkyrja\Dispatch\Throwable\Exception\NoClassException;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
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
        $class    = InvalidDispatcherClass::class;

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
        $this->expectException(NoClassException::class);
        $this->expectExceptionMessage('No class set');

        $constant = 'TEST';

        $dispatch = new ConstantDispatch(constant: $constant);

        $dispatch->getClass();
    }
}
