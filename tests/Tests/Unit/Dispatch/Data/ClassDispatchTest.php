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
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the ClassDispatch.
 */
final class ClassDispatchTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testClass(): void
    {
        $class = InvalidDispatcherClass::class;

        $dispatch = new ClassDispatch(class: $class);

        self::assertSame($class, $dispatch->getClass());

        $newDispatch = $dispatch->withClass(self::class);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($class, $dispatch->getClass());
        self::assertSame(self::class, $newDispatch->getClass());
        self::assertSame(self::class, $newDispatch->__toString());
    }

    public function testConstructor(): void
    {
        $class        = InvalidDispatcherClass::class;
        $arguments    = ['arg' => 'value'];
        $dependencies = ['dependency' => self::class];

        $dispatch = new ClassDispatch(...[
            'class'        => $class,
            'arguments'    => $arguments,
            'dependencies' => $dependencies,
        ]);

        self::assertSame($class, $dispatch->getClass());
        self::assertSame($arguments, $dispatch->getArguments());
        self::assertSame($dependencies, $dispatch->getDependencies());
    }

    public function testArguments(): void
    {
        $class     = InvalidDispatcherClass::class;
        $arguments = ['test'];

        $dispatch = new ClassDispatch(class: $class);

        self::assertEmpty($dispatch->getArguments());

        $newDispatch = $dispatch->withArguments($arguments);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertEmpty($dispatch->getArguments());
        self::assertSame($arguments, $newDispatch->getArguments());
    }

    public function testDependencies(): void
    {
        $class        = InvalidDispatcherClass::class;
        $dependencies = ['test'];

        $dispatch = new ClassDispatch(class: $class);

        self::assertEmpty($dispatch->getDependencies());

        $newDispatch = $dispatch->withDependencies($dependencies);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertEmpty($dispatch->getDependencies());
        self::assertSame($dependencies, $newDispatch->getDependencies());
    }
}
