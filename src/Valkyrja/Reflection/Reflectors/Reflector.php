<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Reflection\Reflectors;

use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Valkyrja\Application\Application;
use Valkyrja\Reflection\Reflector as ReflectorContract;
use Valkyrja\Support\Providers\Provides;

use function spl_object_id;

/**
 * Class Reflector.
 *
 * @author Melech Mizrachi
 */
class Reflector implements ReflectorContract
{
    use Provides;

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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ReflectorContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->setSingleton(
            ReflectorContract::class,
            new static()
        );
    }

    /**
     * Get a class's reflection.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return ReflectionClass
     */
    public function getClassReflection(string $class): ReflectionClass
    {
        $index = static::CLASS_CACHE . $class;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionClass($class);
    }

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
    public function getPropertyReflection(string $class, string $property): ReflectionProperty
    {
        $index = static::PROPERTY_CACHE . $class . $property;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = $this->getClassReflection($class)->getProperty($property);
    }

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
    public function getMethodReflection(string $class, string $method): ReflectionMethod
    {
        $index = static::METHOD_CACHE . $class . $method;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = $this->getClassReflection($class)->getMethod($method);
    }

    /**
     * Get a function's reflection.
     *
     * @param string $function The function
     *
     * @throws ReflectionException
     *
     * @return ReflectionFunction
     */
    public function getFunctionReflection(string $function): ReflectionFunction
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionFunction($function);
    }

    /**
     * Get a closure's reflection.
     *
     * @param Closure $closure The closure
     *
     * @throws ReflectionException
     *
     * @return ReflectionFunction
     */
    public function getClosureReflection(Closure $closure): ReflectionFunction
    {
        $index = static::CLOSURE_CACHE . spl_object_id($closure);

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionFunction($closure);
    }

    /**
     * Get dependencies from a reflection.
     *
     * @param ReflectionFunctionAbstract $reflection The reflection
     *
     * @return string[]
     */
    public function getDependencies(ReflectionFunctionAbstract $reflection): array
    {
        return $this->getDependenciesFromParameters(...$reflection->getParameters());
    }

    /**
     * Get dependencies from parameters.
     *
     * @param ReflectionParameter[] $parameters The parameters
     *
     * @return string[]
     */
    public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array
    {
        // Setup to find any injectable objects through the service container
        $dependencies = [];

        // Iterate through the method's parameters
        foreach ($parameters as $parameter) {
            // We only care for classes
            if ($parameter->getClass()) {
                // Set the injectable in the array
                $dependencies[] = $parameter->getClass()->getName();
            }
        }

        return $dependencies;
    }
}
