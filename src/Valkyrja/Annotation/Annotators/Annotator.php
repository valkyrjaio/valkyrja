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

namespace Valkyrja\Annotation\Annotators;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotator as AnnotationsContract;
use Valkyrja\Annotation\Enums\Property;
use Valkyrja\Annotation\Filter;
use Valkyrja\Annotation\Filters\Filter as AnnotationsFilter;
use Valkyrja\Annotation\Parser;
use Valkyrja\Annotation\Parsers\Parser as AnnotationsParser;
use Valkyrja\Application\Application;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Support\Providers\Provides;

use function array_merge;

/**
 * Class Annotator.
 *
 * @author Melech Mizrachi
 */
class Annotator implements AnnotationsContract
{
    use Provides;

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
     * Cached reflection classes.
     *
     * @var array
     */
    protected static array $reflections = [];

    /**
     * Cached annotations.
     *
     * @var array
     */
    protected static array $annotations = [];

    /**
     * The filter.
     *
     * @var Filter
     */
    protected Filter $filter;

    /**
     * The parser.
     *
     * @var Parser
     */
    protected Parser $parser;

    /**
     * The reflector.
     *
     * @var Reflector
     */
    protected Reflector $reflector;

    /**
     * Annotations constructor.
     *
     * @param Application $app
     * @param Reflector   $reflector
     */
    public function __construct(Application $app, Reflector $reflector)
    {
        $this->reflector = $reflector;
        $this->filter    = new AnnotationsFilter($this);
        $this->parser    = new AnnotationsParser($app);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            AnnotationsContract::class,
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
            AnnotationsContract::class,
            new static($app, $app->reflector())
        );
    }

    /**
     * Get the filterer.
     *
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * Set the filterer.
     *
     * @param Filter $filter The filter
     *
     * @return void
     */
    public function setFilter(Filter $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * Get the parser.
     *
     * @return Parser
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }

    /**
     * Set the parser.
     *
     * @param Parser $parser The parser
     *
     * @return void
     */
    public function setParser(Parser $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * Get a class's annotations.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function classAnnotations(string $class): array
    {
        $index = static::CLASS_CACHE . $class;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                ],
                ...$this->parser->getAnnotations((string) $this->reflector->getClassReflection($class)->getDocComment())
            );
    }

    /**
     * Get a class's members' annotations.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function classMembersAnnotations(string $class): array
    {
        $index = static::CLASS_MEMBERS_CACHE . $class;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = array_merge(
                $this->propertiesAnnotations($class),
                $this->methodsAnnotations($class)
            );
    }

    /**
     * Get a class's and class's members' annotations.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function classAndMembersAnnotations(string $class): array
    {
        $index = static::CLASS_AND_MEMBERS_CACHE . $class;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = array_merge(
                $this->classAnnotations($class),
                $this->classMembersAnnotations($class)
            );
    }

    /**
     * Get a property's annotations.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function propertyAnnotations(string $class, string $property): array
    {
        $index      = static::PROPERTY_CACHE . $class . $property;
        $reflection = $this->reflector->getPropertyReflection($class, $property);

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                    Property::PROPERTY   => $property,
                    Property::STATIC     => $reflection->isStatic(),
                ],
                ...$this->parser->getAnnotations((string) $reflection->getDocComment())
            );
    }

    /**
     * Get a class's properties' annotations.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function propertiesAnnotations(string $class): array
    {
        $index = static::PROPERTIES_CACHE . $class;

        if (isset(self::$annotations[$index])) {
            return self::$annotations[$index];
        }

        $annotations = [];

        // Get the class's reflection
        // Iterate through the properties
        foreach ($this->reflector->getClassReflection($class)->getProperties() as $property) {
            $index = static::METHOD_CACHE . $class . $property->getName();
            // Set the property's reflection class in the cache
            self::$reflections[$index] = $property;

            // Iterate through all the property's annotations
            foreach ($this->propertyAnnotations($class, $property->getName()) as $propertyAnnotation) {
                // Set the annotation in the list
                $annotations[] = $propertyAnnotation;
            }
        }

        return self::$annotations[$index] = $annotations;
    }

    /**
     * Get a method's annotations.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function methodAnnotations(string $class, string $method): array
    {
        $index      = static::METHOD_CACHE . $class . $method;
        $reflection = $this->reflector->getMethodReflection($class, $method);

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    Property::CLASS_NAME => $class,
                    Property::METHOD     => $method,
                    Property::STATIC     => $reflection->isStatic(),
                ],
                ...$this->parser->getAnnotations((string) $reflection->getDocComment())
            );
    }

    /**
     * Get a class's methods' annotations.
     *
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function methodsAnnotations(string $class): array
    {
        $index = static::METHODS_CACHE . $class;

        if (isset(self::$annotations[$index])) {
            return self::$annotations[$index];
        }

        $annotations = [];

        // Get the class's reflection
        // Iterate through the methods
        foreach ($this->reflector->getClassReflection($class)->getMethods() as $method) {
            $index = static::METHOD_CACHE . $class . $method->getName();
            // Set the method's reflection class in the cache
            self::$reflections[$index] = $method;

            // Iterate through all the method's annotations
            foreach ($this->methodAnnotations($class, $method->getName()) as $methodAnnotation) {
                // Set the annotation in the list
                $annotations[] = $methodAnnotation;
            }
        }

        return self::$annotations[$index] = $annotations;
    }

    /**
     * Get a function's annotations.
     *
     * @param string $function The function
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function functionAnnotations(string $function): array
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    Property::FUNCTION => $function,
                ],
                ...$this->parser->getAnnotations(
                    (string) $this->reflector->getFunctionReflection($function)->getDocComment()
                )
            );
    }

    /**
     * Set the base annotation model values.
     *
     * @param array      $properties     The properties
     * @param Annotation ...$annotations The annotations
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
