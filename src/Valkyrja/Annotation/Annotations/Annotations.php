<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation\Annotations;

use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotations as AnnotationsContract;
use Valkyrja\Annotation\AnnotationsParser;
use Valkyrja\Application\Application;
use Valkyrja\Container\Enums\Contract;
use Valkyrja\Support\Providers\Provides;

/**
 * Class Annotations.
 *
 * @author Melech Mizrachi
 */
class Annotations implements AnnotationsContract
{
    use Provides;

    /**
     * The parser.
     *
     * @var AnnotationsParser
     */
    protected AnnotationsParser $parser;

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
     * Annotations constructor.
     *
     * @param AnnotationsParser $parser The parser
     */
    public function __construct(AnnotationsParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Get the parser.
     *
     * @return AnnotationsParser
     */
    public function getParser(): AnnotationsParser
    {
        return $this->parser;
    }

    /**
     * Set the parser.
     *
     * @param AnnotationsParser $parser The parser
     *
     * @return void
     */
    public function setParser(AnnotationsParser $parser): void
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
                    'class' => $class,
                ],
                ...$this->parser->getAnnotations((string) $this->getClassReflection($class)->getDocComment())
            );
    }

    /**
     * Get a class's annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return array
     */
    public function classAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->classAnnotations($class));
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
     * Get a class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function classMembersAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->classMembersAnnotations($class));
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
     * Get a class's and class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function classAndMembersAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->classAndMembersAnnotations($class));
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
        $index = static::PROPERTY_CACHE . $class . $property;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    'class'    => $class,
                    'property' => $property,
                ],
                ...$this->parser->getAnnotations((string) $this->getPropertyReflection($class, $property)->getDocComment())
            );
    }

    /**
     * Get a property's annotations by type.
     *
     * @param string $type     The type
     * @param string $class    The class
     * @param string $property The property
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function propertyAnnotationsType(string $type, string $class, string $property): array
    {
        return $this->filterAnnotationsByType($type, ...$this->propertyAnnotations($class, $property));
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
        foreach ($this->getClassReflection($class)->getProperties() as $property) {
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
     * Get a class's properties' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function propertiesAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->propertiesAnnotations($class));
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
        $reflection = $this->getMethodReflection($class, $method);

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    'class'  => $class,
                    'method' => $method,
                ],
                ...$this->getReflectionFunctionAnnotations($reflection)
            );
    }

    /**
     * Get a method's annotations by type.
     *
     * @param string $type   The type
     * @param string $class  The class
     * @param string $method The method
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function methodAnnotationsType(string $type, string $class, string $method): array
    {
        return $this->filterAnnotationsByType($type, ...$this->methodAnnotations($class, $method));
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
        foreach ($this->getClassReflection($class)->getMethods() as $method) {
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
     * Get a class's methods' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function methodsAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->methodsAnnotations($class));
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
                    'function' => $function,
                ],
                ...$this->getReflectionFunctionAnnotations($this->getFunctionReflection($function))
            );
    }

    /**
     * Get a function's annotations.
     *
     * @param string $type     The type
     * @param string $function The function
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    public function functionAnnotationsType(string $type, string $function): array
    {
        return $this->filterAnnotationsByType($type, ...$this->functionAnnotations($function));
    }

    /**
     * Filter annotations by type.
     *
     * @param string     $type           The type to match
     * @param Annotation ...$annotations The annotations
     *
     * @return array
     */
    public function filterAnnotationsByType(string $type, Annotation ...$annotations): array
    {
        // Set a list of annotations to return
        $annotationsList = [];

        // Iterate through the annotation
        foreach ($annotations as $annotation) {
            // If the annotation's type matches the type requested
            if ($annotation->getAnnotationType() === $type) {
                // Set the annotation in the list
                $annotationsList[] = $annotation;
            }
        }

        return $annotationsList;
    }

    /**
     * Get a reflection class's annotations.
     *
     * @param ReflectionFunctionAbstract $reflection The reflection class
     *
     * @return Annotation[]
     */
    public function getReflectionFunctionAnnotations(ReflectionFunctionAbstract $reflection): array
    {
        return $this->parser->getAnnotations((string) $reflection->getDocComment());
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
            $annotation->setClass($properties['class'] ?? null);
            $annotation->setProperty($properties['property'] ?? null);
            $annotation->setMethod($properties['method'] ?? null);
            $annotation->setFunction($properties['function'] ?? null);
        }

        return $annotations;
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
            ?? self::$reflections[$index] = new ReflectionProperty($class, $property);
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
            ?? self::$reflections[$index] = new ReflectionMethod($class, $method);
    }

    /**
     * get a function's reflection.
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
     * Get dependencies from parameters.
     *
     * @param ReflectionParameter[] $parameters The parameters
     *
     * @return string[]
     */
    protected function getDependencies(ReflectionParameter ...$parameters): array
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
        $app->container()->singleton(
            AnnotationsContract::class,
            new static(
                $app->container()->getSingleton(
                    Contract::ANNOTATIONS_PARSER
                )
            )
        );
    }
}
