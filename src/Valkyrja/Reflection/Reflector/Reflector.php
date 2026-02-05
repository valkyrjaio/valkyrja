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

namespace Valkyrja\Reflection\Reflector;

use Closure;
use Override;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;
use UnitEnum;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Throwable\Exception\RuntimeException;

use function class_exists;
use function interface_exists;
use function is_string;

class Reflector implements ReflectorContract
{
    /**
     * Cached reflection classes.
     *
     * @var array<string, ReflectionClass>
     */
    protected array $classReflections = [];

    /**
     * Cached reflection classes.
     *
     * @var array<string, ReflectionClassConstant>
     */
    protected array $constantReflections = [];

    /**
     * Cached reflection classes.
     *
     * @var array<string, ReflectionProperty>
     */
    protected array $propertyReflections = [];

    /**
     * Cached reflection classes.
     *
     * @var array<string, ReflectionMethod>
     */
    protected array $methodReflections = [];

    /**
     * Cached reflection classes.
     *
     * @var array<string, ReflectionFunction>
     */
    protected array $functionReflections = [];

    /**
     * @inheritDoc
     */
    #[Override]
    public function forClass(string $class): ReflectionClass
    {
        $index = $class;

        return $this->classReflections[$index]
            ??= new ReflectionClass($class);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forClassConstant(string $class, string $const): ReflectionClassConstant
    {
        $index = $class . $const;

        return $this->constantReflections[$index]
            ??= $this->forClass($class)->getReflectionConstant($const)
            ?: throw new RuntimeException("Failed to retrieve constant $const for $class");
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forClassProperty(string $class, string $property): ReflectionProperty
    {
        $index = $class . $property;

        return $this->propertyReflections[$index]
            ??= $this->forClass($class)->getProperty($property);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forClassMethod(string $class, string $method): ReflectionMethod
    {
        $index = $class . $method;

        return $this->methodReflections[$index]
            ??= $this->forClass($class)->getMethod($method);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forFunction(string $function): ReflectionFunction
    {
        $index = $function;

        return $this->functionReflections[$index]
            ??= new ReflectionFunction($function);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function forClosure(Closure $closure): ReflectionFunction
    {
        return new ReflectionFunction($closure);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDependencies(ReflectionFunctionAbstract $reflection): array
    {
        return $this->getDependenciesFromParameters(...$reflection->getParameters());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDependenciesFromParameters(ReflectionParameter ...$parameters): array
    {
        // Setup to find any injectable objects through the service container
        $dependencies = [];

        // Iterate through the method's parameters
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            $isDependency = $this->determineIfParameterIsADependency($type);

            if ($isDependency) {
                /** @var class-string $name */
                $name = $type->getName();

                // Set the injectable in the array
                $dependencies[$parameter->getName()] = $name;
            }
        }

        return $dependencies;
    }

    /**
     * Determine if the parameter is a dependency.
     *
     * @psalm-assert ReflectionNamedType $type
     *
     * @phpstan-assert ReflectionNamedType $type
     */
    protected function determineIfParameterIsADependency(ReflectionType|null $type = null): bool
    {
        return // The type is a ReflectionNamedType
            $type instanceof ReflectionNamedType
            // The name is valid
            && ($name = $type->getName())
            // The name is a string
            && is_string($name)
            // The class or interface exists
            && (class_exists($name) || interface_exists($name))
            // and it isn't an enum
            && ! is_a($name, UnitEnum::class, true)
            // It's not built in
            && ! $type->isBuiltin();
    }
}
