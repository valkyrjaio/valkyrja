<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Unit\Reflection\Reflector;

use ReflectionException;
use Valkyrja\Container\Manager\Contract\ContainerContract;
use Valkyrja\Reflection\Reflector\Reflector;
use Valkyrja\Reflection\Throwable\Exception\ReflectionInvalidClassConstantException;
use Valkyrja\Tests\Fixtures\Reflection\ReflectableFixture;
use Valkyrja\Tests\Unit\Abstract\TestCase;

/**
 * Test the Reflector class.
 */
final class ReflectorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testForClass(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClass(ReflectableFixture::class);
        $reflection2 = $reflector->forClass(ReflectableFixture::class);

        self::assertSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassConstant(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassConstant(ReflectableFixture::class, 'STRING');
        $reflection2 = $reflector->forClassConstant(ReflectableFixture::class, 'STRING');

        self::assertSame($reflection, $reflection2);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassConstantException(): void
    {
        $this->expectException(ReflectionInvalidClassConstantException::class);

        $reflector = new Reflector();

        $reflector->forClassConstant(ReflectableFixture::class, 'STRING2');
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassProperty(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassProperty(ReflectableFixture::class, 'string');
        $reflection2 = $reflector->forClassProperty(ReflectableFixture::class, 'string');

        $reflection3 = $reflector->forClassProperty(ReflectableFixture::class, 'property');
        $reflection4 = $reflector->forClassProperty(ReflectableFixture::class, 'property');

        self::assertSame($reflection, $reflection2);
        self::assertSame($reflection3, $reflection4);
    }

    /**
     * @throws ReflectionException
     */
    public function testForClassMethod(): void
    {
        $reflector = new Reflector();

        $reflection  = $reflector->forClassMethod(ReflectableFixture::class, 'testStatic');
        $reflection2 = $reflector->forClassMethod(ReflectableFixture::class, 'testStatic');

        $reflection3 = $reflector->forClassMethod(ReflectableFixture::class, 'test');
        $reflection4 = $reflector->forClassMethod(ReflectableFixture::class, 'test');

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

        $reflection   = $reflector->forClassMethod(ReflectableFixture::class, 'test');
        $dependencies = $reflector->getDependencies($reflection);

        self::assertSame(['container' => ContainerContract::class], $dependencies);
    }
}
