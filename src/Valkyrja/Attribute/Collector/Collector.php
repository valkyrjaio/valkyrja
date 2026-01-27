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

namespace Valkyrja\Attribute\Collector;

use Closure;
use Override;
use ReflectionAttribute;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;
use Valkyrja\Attribute\Collector\Contract\CollectorContract;
use Valkyrja\Attribute\Contract\ReflectionAwareAttributeContract;
use Valkyrja\Reflection\Reflector\Contract\ReflectorContract;
use Valkyrja\Reflection\Reflector\Reflector as ReflectorReflector;

class Collector implements CollectorContract
{
    /**
     * Default flags for the getAttributes() method.
     *
     * @var int
     */
    protected static int $defaultFlags = ReflectionAttribute::IS_INSTANCEOF;

    public function __construct(
        protected ReflectorContract $reflection = new ReflectorReflector(),
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forClass(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forClass($class);

        return $this->getInstances(
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forClassMembers(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        return [
            ...$this->forConstants($class, $attribute, $flags),
            ...$this->forProperties($class, $attribute, $flags),
            ...$this->forMethods($class, $attribute, $flags),
        ];
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forClassAndMembers(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        return [
            ...$this->forClass($class, $attribute, $flags),
            ...$this->forClassMembers($class, $attribute, $flags),
        ];
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forConstant(
        string $class,
        string $constant,
        string|null $attribute = null,
        int|null $flags = null
    ): array {
        return $this->forClassMember($attribute, $flags, $this->reflection->forClassConstant($class, $constant));
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forConstants(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        return $this->forClassMember(
            $attribute,
            $flags,
            ...$this->reflection->forClass($class)->getReflectionConstants()
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forProperty(
        string $class,
        string $property,
        string|null $attribute = null,
        int|null $flags = null
    ): array {
        return $this->forClassMember($attribute, $flags, $this->reflection->forClassProperty($class, $property));
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forProperties(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        return $this->forClassMember(
            $attribute,
            $flags,
            ...$this->reflection->forClass($class)->getProperties()
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forMethod(
        string $class,
        string $method,
        string|null $attribute = null,
        int|null $flags = null
    ): array {
        return $this->forClassMember($attribute, $flags, $this->reflection->forClassMethod($class, $method));
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forMethodParameters(
        string $class,
        string $method,
        string|null $attribute = null,
        int|null $flags = null
    ): array {
        return $this->forParameter(
            $attribute,
            $flags,
            ...$this->reflection->forClassMethod($class, $method)->getParameters()
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forMethodParameter(
        string $class,
        string $method,
        string $parameter,
        string|null $attribute = null,
        int|null $flags = null
    ): array {
        $parameters = $this->reflection->forClassMethod($class, $method)->getParameters();

        foreach ($parameters as $reflectionParameter) {
            if ($reflectionParameter->getName() === $parameter) {
                return $this->forParameter(
                    $attribute,
                    $flags,
                    $reflectionParameter
                );
            }
        }

        return [];
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forMethods(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        return $this->forClassMember(
            $attribute,
            $flags,
            ...$this->reflection->forClass($class)->getMethods()
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forFunction(string $function, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forFunction($function);

        return $this->getInstances(
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forFunctionParameters(string $function, string|null $attribute = null, int|null $flags = null): array
    {
        return $this->forParameter(
            $attribute,
            $flags,
            ...$this->reflection->forFunction($function)->getParameters()
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forClosure(Closure $closure, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forClosure($closure);

        return $this->getInstances(
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    #[Override]
    public function forClosureParameters(Closure $closure, string|null $attribute = null, int|null $flags = null): array
    {
        return $this->forParameter(
            $attribute,
            $flags,
            ...$this->reflection->forClosure($closure)->getParameters()
        );
    }

    /**
     * Get a class' members' attributes.
     *
     * @param class-string|null                                           $attribute  [optional] The attribute to return
     * @param ReflectionClassConstant|ReflectionMethod|ReflectionProperty ...$members [optional] The members
     *
     * @return object[]
     */
    protected function forClassMember(
        string|null $attribute = null,
        int|null $flags = null,
        ReflectionClassConstant|ReflectionMethod|ReflectionProperty ...$members
    ): array {
        $instances = [];

        foreach ($members as $member) {
            /** @psalm-suppress PossiblyInvalidArgument $member is absolutely a Reflector */
            $instances = [
                ...$instances,
                ...$this->getInstances(
                    $member,
                    ...$member->getAttributes($attribute, $flags ?? static::$defaultFlags)
                ),
            ];
        }

        return $instances;
    }

    /**
     * Get a parameter' attributes.
     *
     * @param class-string|null   $attribute     [optional] The attribute to return
     * @param ReflectionParameter ...$parameters [optional] The parameters
     *
     * @return object[]
     */
    protected function forParameter(
        string|null $attribute = null,
        int|null $flags = null,
        ReflectionParameter ...$parameters
    ): array {
        $instances = [];

        foreach ($parameters as $parameter) {
            $instances = [
                ...$instances,
                ...$this->getInstances(
                    $parameter,
                    ...$parameter->getAttributes($attribute, $flags ?? static::$defaultFlags)
                ),
            ];
        }

        return $instances;
    }

    /**
     * Set the base annotation model values.
     *
     * @param ReflectionAttribute ...$reflectionAttributes The reflection attributes
     *
     * @return object[]
     */
    protected function getInstances(Reflector $reflection, ReflectionAttribute ...$reflectionAttributes): array
    {
        $instances = [];

        foreach ($reflectionAttributes as $reflectionAttribute) {
            $instance = $reflectionAttribute->newInstance();

            if ($instance instanceof ReflectionAwareAttributeContract) {
                $instance->setReflection($reflection);
            }

            $instances[] = $instance;
        }

        return $instances;
    }
}
