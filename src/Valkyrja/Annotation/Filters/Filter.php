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

namespace Valkyrja\Annotation\Filters;

use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotator;
use Valkyrja\Annotation\Filter as Contract;

use function in_array;

/**
 * Class Filter.
 *
 * @author Melech Mizrachi
 */
class Filter implements Contract
{
    /**
     * The annotations.
     *
     * @var Annotator
     */
    protected Annotator $annotator;

    /**
     * Filter constructor.
     *
     * @param Annotator $annotator
     */
    public function __construct(Annotator $annotator)
    {
        $this->annotator = $annotator;
    }

    /**
     * Get a class's annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classAnnotationsByType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->classAnnotations($class));
    }

    /**
     * Get a class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classMembersAnnotationsByType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->classMembersAnnotations($class));
    }

    /**
     * Get a class's and class's members' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classAndMembersAnnotationsByType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->classAndMembersAnnotations($class));
    }

    /**
     * Get a property's annotations by type.
     *
     * @param string $type     The type
     * @param string $class    The class
     * @param string $property The property
     *
     * @return Annotation[]
     */
    public function propertyAnnotationsByType(string $type, string $class, string $property): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->propertyAnnotations($class, $property));
    }

    /**
     * Get a class's properties' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function propertiesAnnotationsByType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->propertiesAnnotations($class));
    }

    /**
     * Get a method's annotations by type.
     *
     * @param string $type   The type
     * @param string $class  The class
     * @param string $method The method
     *
     * @return Annotation[]
     */
    public function methodAnnotationsByType(string $type, string $class, string $method): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->methodAnnotations($class, $method));
    }

    /**
     * Get a class's methods' annotations by type.
     *
     * @param string $type  The type
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function methodsAnnotationsByType(string $type, string $class): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->methodsAnnotations($class));
    }

    /**
     * Get a function's annotations.
     *
     * @param string $type     The type
     * @param string $function The function
     *
     * @return Annotation[]
     */
    public function functionAnnotationsByType(string $type, string $function): array
    {
        return $this->filterAnnotationsByType($type, ...$this->annotator->functionAnnotations($function));
    }

    /**
     * Filter annotations by type.
     *
     * @param string     $type           The type to match
     * @param Annotation ...$annotations The annotations
     *
     * @return Annotation[]
     */
    public function filterAnnotationsByType(string $type, Annotation ...$annotations): array
    {
        return $this->filterAnnotationsByTypes([$type], ...$annotations);
    }

    /**
     * Filter annotations by types.
     *
     * @param array      $types          The types to match
     * @param Annotation ...$annotations The annotations
     *
     * @return Annotation[]
     */
    public function filterAnnotationsByTypes(array $types, Annotation ...$annotations): array
    {
        // Set a list of annotations to return
        $annotationsList = [];

        // Iterate through the annotation
        foreach ($annotations as $annotation) {
            // If the annotation's type matches the types requested
            if (in_array($annotation->getType(), $types, true)) {
                // Set the annotation in the list
                $annotationsList[] = $annotation;
            }
        }

        return $annotationsList;
    }
}
