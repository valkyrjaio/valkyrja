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

namespace Unit\Dispatcher\Data;

use JsonException;
use stdClass;
use Valkyrja\Dispatcher\Data\MethodDispatch as Dispatch;
use Valkyrja\Dispatcher\Exception\InvalidArgumentException;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * Test the MethodDispatch.
 *
 * @author Melech Mizrachi
 */
class MethodDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testFromArray(): void
    {
        $class  = InvalidDispatcherClass::class;
        $method = 'TEST';
        $array  = [
            'class'        => $class,
            'arguments'    => null,
            'dependencies' => null,
            'method'       => $method,
            'isStatic'     => false,
        ];

        $dispatch = Dispatch::fromArray($array);

        self::assertSame($method, $dispatch->getMethod());
        self::assertSame(Arr::toString($array), (string) $dispatch);
        self::assertSame($array, $dispatch->jsonSerialize());
        self::assertSame(Arr::toString($array), json_encode($dispatch, JSON_THROW_ON_ERROR));
    }

    public function testFromCallableOrArray(): void
    {
        $dispatch  = Dispatch::fromCallableOrArray([InvalidDispatcherClass::class, 'method']);
        $dispatch2 = Dispatch::fromCallableOrArray([InvalidDispatcherClass::class, 'staticMethod']);

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

        Dispatch::fromCallableOrArray('str_replace');
    }

    public function testFromCallableOrArrayEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Dispatch::fromCallableOrArray([]);
    }

    public function testFromCallableOrArrayInvalidArrayClassNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Dispatch::fromCallableOrArray([new stdClass()]);
    }

    public function testFromCallableOrArrayInvalidArrayMissingMethod(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Dispatch::fromCallableOrArray([InvalidDispatcherClass::class]);
    }

    public function testMethod(): void
    {
        $class   = InvalidDispatcherClass::class;
        $method  = 'TEST';
        $method2 = 'TEST2';

        $dispatch = new Dispatch(class: $class, method: $method);

        self::assertSame($method, $dispatch->getMethod());

        $newDispatch = $dispatch->withMethod($method2);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($method, $dispatch->getMethod());
        self::assertSame($method2, $newDispatch->getMethod());
    }

    public function testIsStatic(): void
    {
        $class    = InvalidDispatcherClass::class;
        $method   = 'TEST';
        $dispatch = new Dispatch(class: $class, method: $method);

        self::assertFalse($dispatch->isStatic());

        $newDispatch = $dispatch->withIsStatic(true);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertFalse($dispatch->isStatic());
        self::assertTrue($newDispatch->isStatic());
    }
}
