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

namespace Valkyrja\Annotation;

use ReflectionException;
use Valkyrja\Annotation\Constant\Property;
use Valkyrja\Annotation\Contract\Annotations as Contract;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Annotation\Parser\Contract\Parser;
use Valkyrja\Reflection\Contract\Reflection;

use function array_merge;

/**
 * Class Annotations.
 *
 * @author Melech Mizrachi
 */
class Annotations implements Contract
{
    /**
     * Cache index constants.
     */
    protected const CLASS_CACHE             = 'class';
    protected const CLASS_MEMBERS_CACHE     = 'class.members';
    protected const CLASS_AND_MEMBERS_CACHE = 'class.and.members';
    protected const PROPERTY_CACHE          = 'property';
    protected const PROPERTIES_CACHE        = 'properties';
    protected const METHOD_CACHE            = 'method';
    protected const METHODS_CACHE           = 'methods';
    protected const FUNCTION_CACHE          = 'function';

    /**
     * Cached annotations.
     *
     * @var array<string, Annotation[]>
     */
    protected static array $annotations = [];

    /**
     * Annotations constructor.
     */
    public function __construct(
        protected Parser $parser,
        protected Reflection $reflection
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }

    /**
     * @inheritDoc
     */
    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClass(string $class): array
    {
        $index = static::CLASS_CACHE . $class;

        return self::$annotations[$index]
            ??= $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                ],
                ...$this->parser->getAnnotations((string) $this->reflection->forClass($class)->getDocComment())
            );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassMembers(string $class): array
    {
        $index = static::CLASS_MEMBERS_CACHE . $class;

        return self::$annotations[$index]
            ??= array_merge(
                $this->forClassProperties($class),
                $this->forClassMethods($class)
            );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassAndMembers(string $class): array
    {
        $index = static::CLASS_AND_MEMBERS_CACHE . $class;

        return self::$annotations[$index]
            ??= array_merge(
                $this->forClass($class),
                $this->forClassMembers($class)
            );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassProperty(string $class, string $property): array
    {
        $index      = static::PROPERTY_CACHE . $class . $property;
        $reflection = $this->reflection->forClassProperty($class, $property);

        return self::$annotations[$index]
            ??= $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                    Property::PROPERTY   => $property,
                    Property::STATIC     => $reflection->isStatic(),
                ],
                ...$this->parser->getAnnotations((string) $reflection->getDocComment())
            );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassProperties(string $class): array
    {
        $index = static::PROPERTIES_CACHE . $class;

        if (isset(self::$annotations[$index])) {
            return self::$annotations[$index];
        }

        $annotations = [];

        // Get the class's reflection
        // Iterate through the properties
        foreach ($this->reflection->forClass($class)->getProperties() as $property) {
            // Set the annotations in the list
            $annotations[] = $this->forClassProperty($class, $property->getName());
        }

        return self::$annotations[$index] = array_merge(...$annotations);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassMethod(string $class, string $method): array
    {
        $index      = static::METHOD_CACHE . $class . $method;
        $reflection = $this->reflection->forClassMethod($class, $method);

        return self::$annotations[$index]
            ??= $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                    Property::METHOD     => $method,
                    Property::STATIC     => $reflection->isStatic(),
                ],
                ...$this->parser->getAnnotations((string) $reflection->getDocComment())
            );
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forClassMethods(string $class): array
    {
        $index = static::METHODS_CACHE . $class;

        if (isset(self::$annotations[$index])) {
            return self::$annotations[$index];
        }

        $annotations = [];

        // Get the class's reflection
        // Iterate through the methods
        foreach ($this->reflection->forClass($class)->getMethods() as $method) {
            // Set the annotations in the list
            $annotations[] = $this->forClassMethod($class, $method->getName());
        }

        return self::$annotations[$index] = array_merge(...$annotations);
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function forFunction(string $function): array
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$annotations[$index]
            ??= $this->setAnnotationValues(
                [
                    Property::FUNCTION => $function,
                ],
                ...$this->parser->getAnnotations(
                    (string) $this->reflection->forFunction($function)->getDocComment()
                )
            );
    }

    /**
     * Set the base annotation model values.
     *
     * @param array<string, mixed> $properties     The properties
     * @param Annotation           ...$annotations The annotations
     *
     * @return Annotation[]
     */
    protected function setAnnotationValues(array $properties, Annotation ...$annotations): array
    {
        foreach ($annotations as $annotation) {
            $annotation->setClass($properties[Property::CLASS_NAME] ?? null);
            $annotation->setProperty($properties[Property::PROPERTY] ?? null);
            $annotation->setMethod($properties[Property::METHOD] ?? null);
            $annotation->setFunction($properties[Property::FUNCTION] ?? null);
            $annotation->setStatic($properties[Property::STATIC] ?? false);
        }

        return $annotations;
    }
}
