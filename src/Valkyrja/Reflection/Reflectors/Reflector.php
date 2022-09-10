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

namespace Valkyrja\Reflection\Reflectors;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Valkyrja\Reflection\Reflector as Contract;

use function spl_object_id;

/**
 * Class Reflector.
 *
 * @author Melech Mizrachi
 */
class Reflector implements Contract
{
    /**
     * Cache index constants.
     */
    protected const CLASS_CACHE    = 'class';
    protected const PROPERTY_CACHE = 'property';
    protected const METHOD_CACHE   = 'method';
    protected const FUNCTION_CACHE = 'function';
    protected const CLOSURE_CACHE  = 'closure';

    /**
     * Cached reflection classes.
     *
     * @var array
     */
    protected static array $reflections = [];

    /**
     * @inheritDoc
     */
    public function getClassReflection(string $class): ReflectionClass
    {
        $index = static::CLASS_CACHE . $class;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionClass($class);
    }

    /**
     * @inheritDoc
     */
    public function getPropertyReflection(string $class, string $property): ReflectionProperty
    {
        $index = static::PROPERTY_CACHE . $class . $property;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = $this->getClassReflection($class)->getProperty($property);
    }

    /**
     * @inheritDoc
     */
    public function getMethodReflection(string $class, string $method): ReflectionMethod
    {
        $index = static::METHOD_CACHE . $class . $method;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = $this->getClassReflection($class)->getMethod($method);
    }

    /**
     * @inheritDoc
     */
    public function getFunctionReflection(string $function): ReflectionFunction
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionFunction($function);
    }

    /**
     * @inheritDoc
     */
    public function getClosureReflection(Closure $closure): ReflectionFunction
    {
        $index = static::CLOSURE_CACHE . spl_object_id($closure);

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionFunction($closure);
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(ReflectionFunctionAbstract $reflection): array
    {
        return $this->getDependenciesFromParameters(...$reflection->getParameters());
    }

    /**
     * @inheritDoc
     */
    public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array
    {
        // Setup to find any injectable objects through the service container
        $dependencies = [];

        // Iterate through the method's parameters
        foreach ($parameters as $parameter) {
            // We only care for classes
            if ($parameter->getType()) {
                // Set the injectable in the array
                $dependencies[] = $parameter->getType()->getName();
            }
        }

        return $dependencies;
    }
}
