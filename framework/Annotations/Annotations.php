<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotations;

use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionProperty;

use Valkyrja\Contracts\Annotations\Annotation as AnnotationContract;
use Valkyrja\Contracts\Annotations\Annotations as AnnotationsContract;
use Valkyrja\Contracts\Annotations\AnnotationsParser;

/**
 * Class Annotations
 *
 * @package Valkyrja\Annotations
 *
 * @author  Melech Mizrachi
 */
class Annotations implements AnnotationsContract
{
    /**
     * The parser.
     *
     * @var \Valkyrja\Contracts\Annotations\AnnotationsParser
     */
    protected $parser;

    /**
     * Cached reflection classes.
     *
     * @var array
     */
    protected static $reflections = [];

    /**
     * Cached annotations.
     *
     * @var array
     */
    protected static $annotations = [];

    /**
     * Cache index constants.
     */
    protected const CLASS_CACHE    = 'class';
    protected const PROPERTY_CACHE = 'property';
    protected const METHOD_CACHE   = 'method';
    protected const FUNCTION_CACHE = 'function';

    /**
     * Annotations constructor.
     *
     * @param \Valkyrja\Contracts\Annotations\AnnotationsParser $parser The parser
     */
    public function __construct(AnnotationsParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Get the parser.
     *
     * @return \Valkyrja\Contracts\Annotations\AnnotationsParser
     */
    public function getParser(): AnnotationsParser
    {
        return $this->parser;
    }

    /**
     * Set the parser.
     *
     * @param \Valkyrja\Contracts\Annotations\AnnotationsParser $parser The parser
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
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function classAnnotations(string $class): array
    {
        $index = static::CLASS_CACHE . $class;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    'class' => $class,
                ],
                ...$this->parser->getAnnotations(
                $this->getClassReflection($class)->getDocComment()
            )
            );
    }

    /**
     * Get a class's annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function classAnnotationsType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->classAnnotations($class));
    }

    /**
     * Get a property's annotations.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
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
                ...$this->parser->getAnnotations(
                $this->getPropertyReflection($class, $property)->getDocComment()
            )
            );
    }

    /**
     * Get a property's annotations by type.
     *
     * @param string $type     The type
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
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
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function propertiesAnnotations(string $class): array
    {
        $annotations = [];
        // Get the class's reflection
        $reflection = $this->getClassReflection($class);

        // Iterate through the properties
        foreach ($reflection->getProperties() as $property) {
            $index = static::METHOD_CACHE . $class . $property->getName();
            // Set the property's reflection class in the cache
            self::$reflections[$index] = $property;

            // Iterate through all the property's annotations
            foreach ($this->propertyAnnotations($class, $property->getName()) as $propertyAnnotation) {
                // Set the annotation in the list
                $annotations[] = $propertyAnnotation;
            }
        }

        return $annotations;
    }

    /**
     * Get a class's properties' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
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
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function methodAnnotations(string $class, string $method): array
    {
        $index = static::METHOD_CACHE . $class . $method;
        $reflection = $this->getMethodReflection($class, $method);

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    'class'  => $class,
                    'method' => $method,
                ],
                ...$this->getReflectionFunctionAnnotations(
                $reflection
            )
            );
    }

    /**
     * Get a method's annotations by type.
     *
     * @param string $type   The type
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
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
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function methodsAnnotations(string $class): array
    {
        $annotations = [];
        // Get the class's reflection
        $reflection = $this->getClassReflection($class);

        // Iterate through the methods
        foreach ($reflection->getMethods() as $method) {
            $index = static::METHOD_CACHE . $class . $method->getName();
            // Set the method's reflection class in the cache
            self::$reflections[$index] = $method;

            // Iterate through all the method's annotations
            foreach ($this->methodAnnotations($class, $method->getName()) as $methodAnnotation) {
                // Set the annotation in the list
                $annotations[] = $methodAnnotation;
            }
        }

        return $annotations;
    }

    /**
     * Get a class's methods' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
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
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function functionAnnotations(string $function): array
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$annotations[$index]
            ?? self::$annotations[$index] = $this->setAnnotationValues(
                [
                    'function' => $function,
                ],
                ...$this->getReflectionFunctionAnnotations(
                $this->getFunctionReflection($function)
            )
            );
    }

    /**
     * Get a function's annotations.
     *
     * @param string $type     The type
     * @param string $function The function
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     *
     * @throws \ReflectionException
     */
    public function functionAnnotationsType(string $type, string $function): array
    {
        return $this->filterAnnotationsByType($type, ...$this->functionAnnotations($function));
    }

    /**
     * Filter annotations by type.
     *
     * @param string                                       $type           The type to match
     * @param \Valkyrja\Contracts\Annotations\Annotation[] ...$annotations The annotations
     *
     * @return array
     */
    public function filterAnnotationsByType(string $type, AnnotationContract ...$annotations): array
    {
        // Set a list of annotations to return
        $annotationsList = [];

        // Iterate through the annotation
        foreach ($annotations as $annotation) {
            // If the annotation's type matches the type requested
            if ($annotation->getType() === $type) {
                // Set the annotation in the list
                $annotationsList[] = $annotation;
            }
        }

        return $annotationsList;
    }

    /**
     * Get a reflection class's annotations.
     *
     * @param \ReflectionFunctionAbstract $reflection The reflection class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function getReflectionFunctionAnnotations(ReflectionFunctionAbstract $reflection): array
    {
        return $this->parser->getAnnotations($reflection->getDocComment());
    }

    /**
     * Set the base annotation model values.
     *
     * @param array                                        $properties  The properties
     * @param \Valkyrja\Contracts\Annotations\Annotation[] $annotations The annotations
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    protected function setAnnotationValues(array $properties, AnnotationContract ...$annotations): array
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
     * @return \ReflectionClass
     *
     * @throws \ReflectionException
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
     * @return \ReflectionProperty
     *
     * @throws \ReflectionException
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
     * @return \ReflectionMethod
     *
     * @throws \ReflectionException
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
     * @return \ReflectionFunction
     *
     * @throws \ReflectionException
     */
    public function getFunctionReflection(string $function): ReflectionFunction
    {
        $index = static::FUNCTION_CACHE . $function;

        return self::$reflections[$index]
            ?? self::$reflections[$index] = new ReflectionFunction($function);
    }
}
