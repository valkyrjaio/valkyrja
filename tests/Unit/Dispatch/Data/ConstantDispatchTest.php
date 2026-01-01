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
use Valkyrja\Dispatch\Data\ConstantDispatch as Dispatch;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the ConstantDispatch.
 */
class ConstantDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testConstant(): void
    {
        $constant  = 'TEST';
        $constant2 = 'TEST2';

        $dispatch = new Dispatch(constant: $constant);

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

        $dispatch = new Dispatch(constant: $constant);

        self::assertNull($dispatch->getClass());

        $newDispatch = $dispatch->withClass($class);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertNull($dispatch->getClass());
        self::assertSame($class, $newDispatch->getClass());
        self::assertSame($constant, $dispatch->__toString());
        self::assertSame("$class::$constant", $newDispatch->__toString());
    }
}
