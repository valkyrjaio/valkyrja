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

use stdClass;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the MethodDispatch.
 */
class MethodDispatchTest extends TestCase
{
    public function testFromCallableOrArray(): void
    {
        $dispatch  = MethodDispatch::fromCallableOrArray([InvalidDispatcherClass::class, 'method']);
        $dispatch2 = MethodDispatch::fromCallableOrArray([InvalidDispatcherClass::class, 'staticMethod']);

        self::assertSame('method', $dispatch->getMethod());
        self::assertSame(InvalidDispatcherClass::class, $dispatch->getClass());
        self::assertTrue($dispatch->isStatic());
        self::assertSame('staticMethod', $dispatch2->getMethod());
        self::assertSame(InvalidDispatcherClass::class, $dispatch2->getClass());
        self::assertTrue($dispatch2->isStatic());
    }

    public function testFromCallableOrArrayInvalidArrayCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MethodDispatch::fromCallableOrArray('str_replace');
    }

    public function testFromCallableOrArrayEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MethodDispatch::fromCallableOrArray([]);
    }

    public function testFromCallableOrArrayInvalidArrayClassNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MethodDispatch::fromCallableOrArray([new stdClass()]);
    }

    public function testFromCallableOrArrayInvalidArrayMissingMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);

        MethodDispatch::fromCallableOrArray([InvalidDispatcherClass::class]);
    }

    public function testMethod(): void
    {
        $class   = InvalidDispatcherClass::class;
        $method  = 'TEST';
        $method2 = 'TEST2';

        $dispatch = new MethodDispatch(class: $class, method: $method);

        self::assertSame($method, $dispatch->getMethod());

        $newDispatch = $dispatch->withMethod($method2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($method, $dispatch->getMethod());
        self::assertSame($method2, $newDispatch->getMethod());
        self::assertSame("$class->$method()", $dispatch->__toString());
        self::assertSame("$class->$method2()", $newDispatch->__toString());
    }

    public function testIsStatic(): void
    {
        $class    = InvalidDispatcherClass::class;
        $method   = 'TEST';
        $dispatch = new MethodDispatch(class: $class, method: $method);

        self::assertFalse($dispatch->isStatic());

        $newDispatch = $dispatch->withIsStatic(true);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertFalse($dispatch->isStatic());
        self::assertTrue($newDispatch->isStatic());
        self::assertSame("$class->$method()", $dispatch->__toString());
        self::assertSame("$class::$method()", $newDispatch->__toString());
    }
}
