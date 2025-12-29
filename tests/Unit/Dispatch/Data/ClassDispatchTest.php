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
use Valkyrja\Dispatch\Data\ClassDispatch as Dispatch;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;

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
    public function testClass(): void
    {
        $class = InvalidDispatcherClass::class;

        $dispatch = new Dispatch(class: $class);

        self::assertSame($class, $dispatch->getClass());

        $newDispatch = $dispatch->withClass(self::class);

        self::assertNotSame($dispatch, $newDispatch);
        self::assertSame($class, $dispatch->getClass());
        self::assertSame(self::class, $newDispatch->getClass());
        self::assertSame(self::class, $newDispatch->__toString());
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
