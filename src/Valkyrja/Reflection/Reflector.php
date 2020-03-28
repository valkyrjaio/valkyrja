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

namespace Valkyrja\Reflection;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

/**
 * Interface Reflector.
 *
 * @author Melech Mizrachi
 */
interface Reflector
{
    /**
     * Get a class's reflection.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return ReflectionClass
     */
    public function getClassReflection(string $class): ReflectionClass;

    /**
     * Get a property's reflection.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @throws ReflectionException
     *
     * @return ReflectionProperty
     */
    public function getPropertyReflection(string $class, string $property): ReflectionProperty;

    /**
     * Get a method's reflection.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @throws ReflectionException
     *
     * @return ReflectionMethod
     */
    public function getMethodReflection(string $class, string $method): ReflectionMethod;

    /**
     * Get a function's reflection.
     *
     * @param string $function The function
     *
     * @throws ReflectionException
     *
     * @return ReflectionFunction
     */
    public function getFunctionReflection(string $function): ReflectionFunction;

    /**
     * Get a closure's reflection.
     *
     * @param Closure $closure The closure
     *
     * @throws ReflectionException
     *
     * @return ReflectionFunction
     */
    public function getClosureReflection(Closure $closure): ReflectionFunction;

    /**
     * Get dependencies from a reflection.
     *
     * @param ReflectionFunctionAbstract $reflection The reflection
     *
     * @return string[]
     */
    public function getDependencies(ReflectionFunctionAbstract $reflection): array;

    /**
     * Get dependencies from parameters.
     *
     * @param ReflectionParameter[] $parameters The parameters
     *
     * @return string[]
     */
    public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array;
}
