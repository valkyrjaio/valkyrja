<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Reflection\Reflector\Contract;

use Closure;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

interface ReflectorContract
{
    /**
     * Get a class's reflection.
     *
     * @param class-string $class The class
     *
     * @throws ReflectionException
     */
    public function forClass(string $class): ReflectionClass;

    /**
     * Get a class constant's reflection.
     *
     * @param class-string     $class The class
     * @param non-empty-string $const The constant
     *
     * @throws ReflectionException
     */
    public function forClassConstant(string $class, string $const): ReflectionClassConstant;

    /**
     * Get a class property's reflection.
     *
     * @param class-string     $class    The class
     * @param non-empty-string $property The property
     *
     * @throws ReflectionException
     */
    public function forClassProperty(string $class, string $property): ReflectionProperty;

    /**
     * Get a class method's reflection.
     *
     * @param class-string     $class  The class
     * @param non-empty-string $method The method
     *
     * @throws ReflectionException
     */
    public function forClassMethod(string $class, string $method): ReflectionMethod;

    /**
     * Get a function's reflection.
     *
     * @param callable-string $function The function
     *
     * @throws ReflectionException
     */
    public function forFunction(string $function): ReflectionFunction;

    /**
     * Get a closure's reflection.
     *
     * @param Closure $closure The closure
     *
     * @throws ReflectionException
     */
    public function forClosure(Closure $closure): ReflectionFunction;

    /**
     * Get dependencies from a reflection.
     *
     * @param ReflectionFunctionAbstract $reflection The reflection
     *
     * @return array<non-empty-string, class-string>
     */
    public function getDependencies(ReflectionFunctionAbstract $reflection): array;

    /**
     * Get dependencies from parameters.
     *
     * @param ReflectionParameter ...$parameters The parameters
     *
     * @return array<non-empty-string, class-string>
     */
    public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array;
}
