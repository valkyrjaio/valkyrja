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

namespace Valkyrja\Tests\Unit\Dispatcher\Data;

use JsonException;
use Valkyrja\Dispatcher\Data\ClassDispatch as Dispatch;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;
use Valkyrja\Type\BuiltIn\Support\Arr;

use function json_encode;

/**
 * Test the ClassDispatch.
 *
 * @author Melech Mizrachi
 */
class ClassDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testFromArray(): void
    {
        $class = 'Test';
        $array = [
            'class'        => $class,
            'arguments'    => null,
            'dependencies' => null,
        ];

        $dispatch = Dispatch::fromArray($array);

        self::assertSame($class, $dispatch->getClass());
        self::assertSame(Arr::toString($array), (string) $dispatch);
        self::assertSame($array, $dispatch->jsonSerialize());
        self::assertSame(Arr::toString($array), json_encode($dispatch, JSON_THROW_ON_ERROR));
    }

    public function testClass(): void
    {
        $class = InvalidDispatcherClass::class;

        $dispatch = new Dispatch(class: $class);

        self::assertSame($class, $dispatch->getClass());

        $newDispatch = $dispatch->withClass(self::class);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($class, $dispatch->getClass());
        self::assertSame(self::class, $newDispatch->getClass());
    }

    public function testArguments(): void
    {
        $class     = InvalidDispatcherClass::class;
        $arguments = ['test'];

        $dispatch = new Dispatch(class: $class);

        self::assertNull($dispatch->getArguments());

        $newDispatch = $dispatch->withArguments($arguments);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertNull($dispatch->getArguments());
        self::assertSame($arguments, $newDispatch->getArguments());
    }

    public function testDependencies(): void
    {
        $class        = InvalidDispatcherClass::class;
        $dependencies = ['test'];

        $dispatch = new Dispatch(class: $class);

        self::assertNull($dispatch->getDependencies());

        $newDispatch = $dispatch->withDependencies($dependencies);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertNull($dispatch->getDependencies());
        self::assertSame($dependencies, $newDispatch->getDependencies());
    }
}
