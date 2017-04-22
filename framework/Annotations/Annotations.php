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
    protected $reflections = [];

    /**
     * Cached annotations.
     *
     * @var array
     */
    protected $annotations = [];

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
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function classAnnotations(string $class): array
    {
        $index = static::CLASS_CACHE . $class;

        return $this->annotations[$index]
            ?? $this->annotations[$index] = $this->setAnnotationValues(
                [
                    'class' => $class,
                ],
                ...$this->parser->getAnnotations(
                $this->getClassReflection($class)->getDocComment()
            )
            );
    }

    /**
     * Get a property's annotations.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function propertyAnnotations(string $class, string $property): array
    {
        $index = static::PROPERTY_CACHE . $class . $property;

        return $this->annotations[$index]
            ?? $this->annotations[$index] = $this->setAnnotationValues(
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
     * Get a method's annotations.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function methodAnnotations(string $class, string $method): array
    {
        $index = static::METHOD_CACHE . $class . $method;

        return $this->annotations[$index]
            ?? $this->annotations[$index] = $this->setAnnotationValues(
                [
                    'class'  => $class,
                    'method' => $method,
                ],
                ...$this->getReflectionFunctionAnnotations(
                $this->getMethodReflection($class, $method)
            )
            );
    }

    /**
     * Get a class's methods' annotations.
     *
     * @param string $class
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function methodsAnnotations(string $class): array
    {
        $annotations = [];
        // Get the class's reflection
        $reflection = $this->getClassReflection($class);

        // Iterate through the methods
        foreach ($reflection->getMethods() as $method) {
            // Get the annotations for this method
            $methodAnnotations = $this->setAnnotationValues(
                [
                    'class'  => $class,
                    'method' => $method->getName(),
                ],
                ...$this->getReflectionFunctionAnnotations($method)
            );

            $index = static::METHOD_CACHE . $class . $method->getName();
            // Set the method's reflection class in the cache
            $this->reflections[$index] = $method;
            // Set the results in the annotations cache for later re-use
            $this->annotations[$index] = $methodAnnotations;

            // Iterate through all the method annotations
            foreach ($methodAnnotations as $methodAnnotation) {
                // Set the annotation in the list
                $annotations[] = $methodAnnotation;
            }
        }

        return $annotations;
    }

    /**
     * Get a function's annotations.
     *
     * @param string $function The function
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function functionAnnotations(string $function): array
    {
        $index = static::FUNCTION_CACHE . $function;

        return $this->annotations[$index]
            ?? $this->annotations[$index] = $this->setAnnotationValues(
                [
                    'function' => $function,
                ],
                ...$this->getReflectionFunctionAnnotations(
                $this->getFunctionReflection($function)
            )
            );
    }

    /**
     * Get a reflection class's annotations.
     *
     * @param \ReflectionFunctionAbstract $reflection The reflection class
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    public function getReflectionFunctionAnnotations(ReflectionFunctionAbstract $reflection): array
    {
        return $this->parser->getAnnotations($reflection->getDocComment());
    }

    /**
     * Set the base annotation model values.
     *
     * @param array                              $properties  The properties
     * @param \Valkyrja\Annotations\Annotation[] $annotations The annotations
     *
     * @return \Valkyrja\Annotations\Annotation[]
     */
    protected function setAnnotationValues(array $properties, Annotation ...$annotations): array
    {
        foreach ($annotations as $annotation) {
            if (isset($properties['class'])) {
                $annotation->setClass($properties['class']);
            }

            if (isset($properties['property'])) {
                $annotation->setProperty($properties['property']);
            }

            if (isset($properties['method'])) {
                $annotation->setMethod($properties['method']);
            }

            if (isset($properties['function'])) {
                $annotation->setFunction($properties['function']);
            }
        }

        return $annotations;
    }

    /**
     * Get a class's reflection.
     *
     * @param string $class The class
     *
     * @return \ReflectionClass
     */
    public function getClassReflection(string $class): ReflectionClass
    {
        $index = static::CLASS_CACHE . $class;

        return $this->reflections[$index]
            ?? $this->reflections[$index] = new ReflectionClass($class);
    }

    /**
     * Get a property's reflection.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \ReflectionProperty
     */
    public function getPropertyReflection(string $class, string $property): ReflectionProperty
    {
        $index = static::PROPERTY_CACHE . $class . $property;

        return $this->reflections[$index]
            ?? $this->reflections[$index] = new ReflectionProperty($class, $property);
    }

    /**
     * Get a method's reflection.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \ReflectionMethod
     */
    public function getMethodReflection(string $class, string $method): ReflectionMethod
    {
        $index = static::METHOD_CACHE . $class . $method;

        return $this->reflections[$index]
            ?? $this->reflections[$index] = new ReflectionMethod($class, $method);
    }

    /**
     * get a function's reflection.
     *
     * @param string $function The function
     *
     * @return \ReflectionFunction
     */
    public function getFunctionReflection(string $function): ReflectionFunction
    {
        $index = static::FUNCTION_CACHE . $function;

        return $this->reflections[$index]
            ?? $this->reflections[$index] = new ReflectionFunction($function);
    }
}
