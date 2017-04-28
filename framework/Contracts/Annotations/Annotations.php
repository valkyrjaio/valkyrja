<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Annotations;

use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Interface Annotations
 *
 * @package Valkyrja\Contracts\Annotations
 *
 * @author  Melech Mizrachi
 */
interface Annotations
{
    /**
     * Annotations constructor.
     *
     * @param \Valkyrja\Contracts\Annotations\AnnotationsParser $parser The parser
     */
    public function __construct(AnnotationsParser $parser);

    /**
     * Get the parser.
     *
     * @return \Valkyrja\Contracts\Annotations\AnnotationsParser
     */
    public function getParser(): AnnotationsParser;

    /**
     * Set the parser.
     *
     * @param \Valkyrja\Contracts\Annotations\AnnotationsParser $parser The parser
     *
     * @return void
     */
    public function setParser(AnnotationsParser $parser): void;

    /**
     * Get a class's annotations.
     *
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function classAnnotations(string $class): array;

    /**
     * Get a class's annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return array
     */
    public function classAnnotationsType(string $type, string $class): array;

    /**
     * Get a class's members' annotations.
     *
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function classMembersAnnotations(string $class): array;

    /**
     * Get a class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function classMembersAnnotationsType(string $type, string $class): array;

    /**
     * Get a class's and class's members' annotations.
     *
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function classAndMembersAnnotations(string $class): array;

    /**
     * Get a class's and class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function classAndMembersAnnotationsType(string $type, string $class): array;

    /**
     * Get a property's annotations.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function propertyAnnotations(string $class, string $property): array;

    /**
     * Get a property's annotations by type.
     *
     * @param string $type     The type
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function propertyAnnotationsType(string $type, string $class, string $property): array;

    /**
     * Get a class's properties' annotations.
     *
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function propertiesAnnotations(string $class): array;

    /**
     * Get a class's properties' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function propertiesAnnotationsType(string $type, string $class): array;

    /**
     * Get a method's annotations.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function methodAnnotations(string $class, string $method): array;

    /**
     * Get a method's annotations by type.
     *
     * @param string $type   The type
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function methodAnnotationsType(string $type, string $class, string $method): array;

    /**
     * Get a class's methods' annotations.
     *
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function methodsAnnotations(string $class): array;

    /**
     * Get a class's methods' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function methodsAnnotationsType(string $type, string $class): array;

    /**
     * Get a function's annotations.
     *
     * @param string $function The function
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function functionAnnotations(string $function): array;

    /**
     * Get a function's annotations.
     *
     * @param string $type     The type
     * @param string $function The function
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function functionAnnotationsType(string $type, string $function): array;

    /**
     * Filter annotations by type.
     *
     * @param string                                       $type           The type to match
     * @param \Valkyrja\Contracts\Annotations\Annotation[] ...$annotations The annotations
     *
     * @return array
     */
    public function filterAnnotationsByType(string $type, Annotation ...$annotations): array;

    /**
     * Get a reflection class's annotations.
     *
     * @param \ReflectionFunctionAbstract $reflection The reflection class
     *
     * @return \Valkyrja\Contracts\Annotations\Annotation[]
     */
    public function getReflectionFunctionAnnotations(ReflectionFunctionAbstract $reflection): array;

    /**
     * Get a class's reflection.
     *
     * @param string $class The class
     *
     * @return \ReflectionClass
     */
    public function getClassReflection(string $class): ReflectionClass;

    /**
     * Get a property's reflection.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return \ReflectionProperty
     */
    public function getPropertyReflection(string $class, string $property): ReflectionProperty;

    /**
     * Get a method's reflection.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @return \ReflectionMethod
     */
    public function getMethodReflection(string $class, string $method): ReflectionMethod;

    /**
     * get a function's reflection.
     *
     * @param string $function The function
     *
     * @return \ReflectionFunction
     */
    public function getFunctionReflection(string $function): ReflectionFunction;
}
