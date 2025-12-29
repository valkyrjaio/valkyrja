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

namespace Valkyrja\Tests\Unit\Reflection\Reflector;

use ReflectionException;
use Valkyrja\Container\Manager\Contract\Container;
use Valkyrja\Reflection\Exception\RuntimeException;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Tests\Classes\Reflection\ReflectableClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the Reflector class.
 *
 * @author Melech Mizrachi
 */
class ReflectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testForClass(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClass(ReflectableClass::class);
        $reflection2 = $reflector->forClass(ReflectableClass::class);

        self::assertSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassConstant(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassConstant(ReflectableClass::class, 'STRING');
        $reflection2 = $reflector->forClassConstant(ReflectableClass::class, 'STRING');

        self::assertSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassConstantException(): void
    {
        $this->expectException(RuntimeException::class);

        $reflector = new Reflector();

        $reflector->forClassConstant(ReflectableClass::class, 'STRING2');
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassProperty(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassProperty(ReflectableClass::class, 'string');
        $reflection2 = $reflector->forClassProperty(ReflectableClass::class, 'string');

        $reflection3 = $reflector->forClassProperty(ReflectableClass::class, 'property');
        $reflection4 = $reflector->forClassProperty(ReflectableClass::class, 'property');

        self::assertSame($reflection, $reflection2);
        self::assertSame($reflection3, $reflection4);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassMethod(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassMethod(ReflectableClass::class, 'testStatic');
        $reflection2 = $reflector->forClassMethod(ReflectableClass::class, 'testStatic');

        $reflection3 = $reflector->forClassMethod(ReflectableClass::class, 'test');
        $reflection4 = $reflector->forClassMethod(ReflectableClass::class, 'test');

        self::assertSame($reflection, $reflection2);
        self::assertSame($reflection3, $reflection4);
    }

    /**
     * @throws ReflectionException
     */
    public function testForFunction(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forFunction('array_merge');
        $reflection2 = $reflector->forFunction('array_merge');

        self::assertSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClosure(): void
    {
        $function = static fn (): string => 'string';

        $reflector = new Reflector();

        $reflection  = $reflector->forClosure($function);
        $reflection2 = $reflector->forClosure($function);

        self::assertNotSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testgetDependencies(): void
    {
        $reflector = new Reflector();

        $reflection   = $reflector->forClassMethod(ReflectableClass::class, 'test');
        $dependencies = $reflector->getDependencies($reflection);

        self::assertSame(['container' => Container::class], $dependencies);
    }
}
