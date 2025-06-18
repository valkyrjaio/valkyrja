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

namespace Valkyrja\Attribute;

use Closure;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;
use Valkyrja\Attribute\Constant\AttributeProperty;
use Valkyrja\Attribute\Contract\Attributes as Contract;
use Valkyrja\Dispatcher\Data\CallableDispatch;
use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\ConstantDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Dispatcher\Data\PropertyDispatch;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class Attributes.
 *
 * @author Melech Mizrachi
 */
class Attributes implements Contract
{
    /**
     * Default flags for the getAttributes() method.
     *
     * @var int
     */
    protected static int $defaultFlags = ReflectionAttribute::IS_INSTANCEOF;

    /**
     * Attributes constructor.
     */
    public function __construct(
        protected Reflection $reflection = new \Valkyrja\Reflection\Reflection(),
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClass(string $class, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forClass($class);

        return $this->getInstances(
            [
                AttributeProperty::CLASS_NAME => $class,
            ],
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
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
    public function forFunction(string $function, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forFunction($function);

        return $this->getInstances(
            [
                AttributeProperty::FUNCTION => $function,
            ],
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
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
    public function forClosure(Closure $closure, string|null $attribute = null, int|null $flags = null): array
    {
        $reflection = $this->reflection->forClosure($closure);

        return $this->getInstances(
            [
                AttributeProperty::CLOSURE => $closure,
            ],
            $reflection,
            ...$reflection->getAttributes($attribute, $flags ?? static::$defaultFlags)
        );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
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
                    [
                        AttributeProperty::CLASS_NAME => $member->getDeclaringClass()->getName(),
                        AttributeProperty::CONSTANT   => $member instanceof ReflectionClassConstant
                            ? $member->getName()
                            : null,
                        AttributeProperty::PROPERTY   => $member instanceof ReflectionProperty
                            ? $member->getName()
                            : null,
                        AttributeProperty::METHOD     => $member instanceof ReflectionMethod
                            ? $member->getName()
                            : null,
                        AttributeProperty::STATIC     => $member instanceof ReflectionProperty || $member instanceof ReflectionMethod
                            ? $member->isStatic()
                            : null,
                    ],
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
            $className = $parameter->getDeclaringClass()?->getName();

            $instances = [
                ...$instances,
                ...$this->getInstances(
                    [
                        AttributeProperty::CLASS_NAME => $className,
                        AttributeProperty::METHOD     => $className ? $parameter->getDeclaringFunction()->getName() : null,
                        AttributeProperty::FUNCTION   => $className === null ? $parameter->getDeclaringFunction()->getName() : null,
                        AttributeProperty::STATIC     => $parameter->getDeclaringFunction()->isStatic(),
                        AttributeProperty::OPTIONAL   => $parameter->isOptional(),
                        AttributeProperty::DEFAULT    => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
                        AttributeProperty::NAME       => $parameter->getName(),
                    ],
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
     * @param array<string, mixed> $properties              The properties
     * @param ReflectionAttribute  ...$reflectionAttributes The reflection attributes
     *
     * @return object[]
     */
    protected function getInstances(array $properties, Reflector $reflection, ReflectionAttribute ...$reflectionAttributes): array
    {
        $instances = [];

        foreach ($reflectionAttributes as $reflectionAttribute) {
            $instance = $reflectionAttribute->newInstance();

            if (method_exists($instance, 'setReflection')) {
                $instance->setReflection($reflection);
            }

            if (method_exists($instance, 'withDispatch')) {
                /** @var object $instance */
                $instance = $instance->withDispatch(
                    match (true) {
                        $reflection instanceof ReflectionMethod        => new MethodDispatch(
                            class: $reflection->getDeclaringClass()->getName(),
                            method: $reflection->getName(),
                            isStatic: $reflection->isStatic()
                        ),
                        $reflection instanceof ReflectionProperty      => new PropertyDispatch(
                            class: $reflection->getDeclaringClass()->getName(),
                            property: $reflection->getName(),
                            isStatic: $reflection->isStatic()
                        ),
                        $reflection instanceof ReflectionClass         => new ClassDispatch(
                            class: $reflection->getName(),
                        ),
                        $reflection instanceof ReflectionClassConstant => new ConstantDispatch(
                            constant: $reflection->getName(),
                            class: $reflection->getDeclaringClass()->getName()
                        ),
                        $reflection instanceof ReflectionFunction
                        && ! $reflection->isClosure()                  => new CallableDispatch(
                            callable: $reflection->getName()
                        ),
                        default                                        => $instance->getDispatch(),
                    }
                );
            }

            if (isset($properties[AttributeProperty::CLASS_NAME]) && method_exists($instance, 'setClass')) {
                $instance->setClass($properties[AttributeProperty::CLASS_NAME]);
            }

            if (isset($properties[AttributeProperty::CONSTANT]) && method_exists($instance, 'setConstant')) {
                $instance->setConstant($properties[AttributeProperty::CONSTANT]);
            }

            if (isset($properties[AttributeProperty::PROPERTY]) && method_exists($instance, 'setProperty')) {
                $instance->setProperty($properties[AttributeProperty::PROPERTY]);
            }

            if (isset($properties[AttributeProperty::METHOD]) && method_exists($instance, 'setMethod')) {
                $instance->setMethod($properties[AttributeProperty::METHOD]);
            }

            if (isset($properties[AttributeProperty::STATIC]) && method_exists($instance, 'setStatic')) {
                $instance->setStatic($properties[AttributeProperty::STATIC]);
            }

            if (isset($properties[AttributeProperty::OPTIONAL]) && method_exists($instance, 'setIsOptional')) {
                $instance->setIsOptional($properties[AttributeProperty::OPTIONAL]);
            }

            if (isset($properties[AttributeProperty::DEFAULT]) && method_exists($instance, 'setDefault')) {
                $instance->setDefault($properties[AttributeProperty::DEFAULT]);
            }

            if (isset($properties[AttributeProperty::NAME]) && method_exists($instance, 'setName')) {
                $instance->setName($properties[AttributeProperty::NAME]);
            }

            $instances[] = $instance;
        }

        return $instances;
    }
}
