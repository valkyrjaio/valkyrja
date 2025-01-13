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
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use UnitEnum;
use Valkyrja\Reflection\Contract\Reflection as Contract;

use function class_exists;
use function interface_exists;
use function is_callable;
use function spl_object_id;

/**
 * Class Reflection.
 *
 * @author Melech Mizrachi
 */
class Reflection implements Contract
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
    public function forClass(string $class): ReflectionClass
    {
        $index = static::CLASS_CACHE . $class;

        return self::$reflections[$index]
            ??= new ReflectionClass($class);
    }

    /**
     * @inheritDoc
     */
    public function forClassConstant(string $class, string $const): ReflectionClassConstant
    {
        $index = static::PROPERTY_CACHE . $class . $const;

        return self::$reflections[$index]
            ??= $this->forClass($class)->getReflectionConstant($const);
    }

    /**
     * @inheritDoc
     */
    public function forClassProperty(string $class, string $property): ReflectionProperty
    {
        $index = static::PROPERTY_CACHE . $class . $property;

        return self::$reflections[$index]
            ??= $this->forClass($class)->getProperty($property);
    }

    /**
     * @inheritDoc
     */
    public function forClassMethod(string $class, string $method): ReflectionMethod
    {
        $index = static::METHOD_CACHE . $class . $method;

        return self::$reflections[$index]
            ??= $this->forClass($class)->getMethod($method);
    }

    /**
     * @inheritDoc
     */
    public function forFunction(string $function): ReflectionFunction
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$reflections[$index]
            ??= new ReflectionFunction($function);
    }

    /**
     * @inheritDoc
     */
    public function forClosure(Closure $closure): ReflectionFunction
    {
        $index = static::CLOSURE_CACHE . spl_object_id($closure);

        return self::$reflections[$index]
            ??= new ReflectionFunction($closure);
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
            $type = $parameter->getType();

            if (
                // The type is a ReflectionNamedType
                $type instanceof ReflectionNamedType
                // The name is valid
                && ($name = $type->getName())
                // The name is not a callable
                && ! is_callable($name)
                // The class or interface exists
                && (class_exists($name) || interface_exists($name))
                // and it isn't an enum
                && ! is_a($name, UnitEnum::class, true)
                // It's not built in
                && ! $type->isBuiltin()
            ) {
                // Set the injectable in the array
                $dependencies[] = $name;
            }
        }

        return $dependencies;
    }
}
