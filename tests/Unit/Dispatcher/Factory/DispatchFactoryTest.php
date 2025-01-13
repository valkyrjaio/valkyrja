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

namespace Valkyrja\Tests\Unit\Dispatcher\Factory;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Valkyrja\Dispatcher\Data\CallableDispatch;
use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\ConstantDispatch;
use Valkyrja\Dispatcher\Data\GlobalVariableDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Dispatcher\Data\PropertyDispatch;
use Valkyrja\Dispatcher\Exception\InvalidArgumentException;
use Valkyrja\Dispatcher\Factory\DispatchFactory;
use Valkyrja\Tests\Classes\Dispatcher\InvalidDispatcherClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the DispatchFactory.
 *
 * @author Melech Mizrachi
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

    public function testFromArray(): void
    {
        $class    = 'Test';
        $method   = 'foo';
        $property = 'bar';
        $constant = 'TEST';
        $callable = 'str_replace';
        $variable = '_GET';

        $methodDispatchArray         = [
            'class'    => $class,
            'method'   => $method,
            'isStatic' => false,
        ];
        $methodDispatchArrayStatic   = [
            'class'    => $class,
            'method'   => $method,
            'isStatic' => true,
        ];
        $propertyDispatchArray       = [
            'class'    => $class,
            'property' => $property,
            'isStatic' => false,
        ];
        $propertyDispatchArrayStatic = [
            'class'    => $class,
            'property' => $property,
            'isStatic' => true,
        ];
        $constantArray               = [
            'constant' => $constant,
        ];
        $constantArrayWithClass      = [
            'constant' => $constant,
            'class'    => $class,
        ];
        $classArray                  = [
            'class' => $class,
        ];
        $callableArray               = [
            'callable' => $callable,
        ];
        $variableArray               = [
            'variable' => $variable,
        ];

        $methodDispatch         = DispatchFactory::fromArray($methodDispatchArray);
        $methodStaticDispatch   = DispatchFactory::fromArray($methodDispatchArrayStatic);
        $propertyDispatch       = DispatchFactory::fromArray($propertyDispatchArray);
        $propertyStaticDispatch = DispatchFactory::fromArray($propertyDispatchArrayStatic);
        $constantDispatch       = DispatchFactory::fromArray($constantArray);
        $classConstantDispatch  = DispatchFactory::fromArray($constantArrayWithClass);
        $classDispatch          = DispatchFactory::fromArray($classArray);
        $callableDispatch       = DispatchFactory::fromArray($callableArray);
        $variableDispatch       = DispatchFactory::fromArray($variableArray);

        self::assertInstanceOf(MethodDispatch::class, $methodDispatch);
        self::assertInstanceOf(MethodDispatch::class, $methodStaticDispatch);
        self::assertInstanceOf(PropertyDispatch::class, $propertyDispatch);
        self::assertInstanceOf(PropertyDispatch::class, $propertyStaticDispatch);
        self::assertInstanceOf(ConstantDispatch::class, $constantDispatch);
        self::assertInstanceOf(ConstantDispatch::class, $classConstantDispatch);
        self::assertInstanceOf(ClassDispatch::class, $classDispatch);
        self::assertInstanceOf(CallableDispatch::class, $callableDispatch);
        self::assertInstanceOf(GlobalVariableDispatch::class, $variableDispatch);

        self::assertSame($class, $methodDispatch->getClass());
        self::assertSame($method, $methodDispatch->getMethod());
        self::assertFalse($methodDispatch->isStatic());
        self::assertSame($class, $methodStaticDispatch->getClass());
        self::assertSame($method, $methodStaticDispatch->getMethod());
        self::assertTrue($methodStaticDispatch->isStatic());

        self::assertSame($class, $propertyDispatch->getClass());
        self::assertSame($property, $propertyDispatch->getProperty());
        self::assertFalse($propertyDispatch->isStatic());
        self::assertSame($class, $propertyStaticDispatch->getClass());
        self::assertSame($property, $propertyStaticDispatch->getProperty());
        self::assertTrue($propertyStaticDispatch->isStatic());

        self::assertNull($constantDispatch->getClass());
        self::assertSame($constant, $constantDispatch->getConstant());

        self::assertSame($class, $classConstantDispatch->getClass());
        self::assertSame($constant, $classConstantDispatch->getConstant());

        self::assertSame($class, $classDispatch->getClass());

        self::assertSame($callable, $callableDispatch->getCallable());

        self::assertSame($variable, $variableDispatch->getVariable());
    }

    public function testFromArrayException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        DispatchFactory::fromArray(['random' => 'test']);
    }
}
