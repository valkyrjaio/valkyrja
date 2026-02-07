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

namespace Valkyrja\Tests\Unit\Dispatch\Factory;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Valkyrja\Dispatch\Data\CallableDispatch;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Dispatch\Data\ConstantDispatch;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Data\PropertyDispatch;
use Valkyrja\Dispatch\Factory\DispatchFactory;
use Valkyrja\Tests\Classes\Dispatch\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the DispatchFactory.
 */
class DispatchFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testFromReflection(): void
    {
        $class          = InvalidDispatcherClass::class;
        $constant       = 'TEST';
        $method         = 'method';
        $staticMethod   = 'staticMethod';
        $property       = 'property';
        $staticProperty = 'staticProperty';
        $callable       = 'str_replace';

        $classConstantReflection       = new ReflectionClassConstant($class, $constant);
        $classMethodReflection         = new ReflectionMethod($class, $method);
        $classStaticMethodReflection   = new ReflectionMethod($class, $staticMethod);
        $classPropertyReflection       = new ReflectionProperty($class, $property);
        $classStaticPropertyReflection = new ReflectionProperty($class, $staticProperty);
        $classReflection               = new ReflectionClass($class);
        $functionReflection            = new ReflectionFunction($callable);

        $classConstantDispatch       = DispatchFactory::fromReflection($classConstantReflection);
        $classMethodDispatch         = DispatchFactory::fromReflection($classMethodReflection);
        $classStaticMethodDispatch   = DispatchFactory::fromReflection($classStaticMethodReflection);
        $classPropertyDispatch       = DispatchFactory::fromReflection($classPropertyReflection);
        $classStaticPropertyDispatch = DispatchFactory::fromReflection($classStaticPropertyReflection);
        $classDispatch               = DispatchFactory::fromReflection($classReflection);
        $callableDispatch            = DispatchFactory::fromReflection($functionReflection);

        self::assertInstanceOf(MethodDispatch::class, $classMethodDispatch);
        self::assertInstanceOf(MethodDispatch::class, $classStaticMethodDispatch);
        self::assertInstanceOf(PropertyDispatch::class, $classPropertyDispatch);
        self::assertInstanceOf(PropertyDispatch::class, $classStaticPropertyDispatch);
        self::assertInstanceOf(ConstantDispatch::class, $classConstantDispatch);
        self::assertInstanceOf(ClassDispatch::class, $classDispatch);
        self::assertInstanceOf(CallableDispatch::class, $callableDispatch);

        self::assertSame($class, $classMethodDispatch->getClass());
        self::assertSame($method, $classMethodDispatch->getMethod());
        self::assertFalse($classMethodDispatch->isStatic());

        self::assertSame($class, $classStaticMethodDispatch->getClass());
        self::assertSame($staticMethod, $classStaticMethodDispatch->getMethod());
        self::assertTrue($classStaticMethodDispatch->isStatic());

        self::assertSame($class, $classPropertyDispatch->getClass());
        self::assertSame($property, $classPropertyDispatch->getProperty());
        self::assertFalse($classPropertyDispatch->isStatic());

        self::assertSame($class, $classStaticPropertyDispatch->getClass());
        self::assertSame($staticProperty, $classStaticPropertyDispatch->getProperty());
        self::assertTrue($classStaticPropertyDispatch->isStatic());

        self::assertSame($class, $classConstantDispatch->getClass());
        self::assertSame($constant, $classConstantDispatch->getConstant());

        self::assertSame($class, $classDispatch->getClass());

        self::assertSame($callable, $callableDispatch->getCallable());
    }
}
