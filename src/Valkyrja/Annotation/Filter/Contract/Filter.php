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

namespace Valkyrja\Annotation\Filter\Contract;

use Valkyrja\Annotation\Model\Contract\Annotation;

/**
 * Interface AnnotationsFilter.
 *
 * @author Melech Mizrachi
 */
interface Filter
{
    /**
     * Get a class's annotations by type.
     *
     * @param string       $type  The type
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function classAnnotationsByType(string $type, string $class): array;

    /**
     * Get a class's members' annotations by type.
     *
     * @param string       $type  The type
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function classMembersAnnotationsByType(string $type, string $class): array;

    /**
     * Get a class's and class's members' annotations by type.
     *
     * @param string       $type  The type
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function classAndMembersAnnotationsByType(string $type, string $class): array;

    /**
     * Get a property's annotations by type.
     *
     * @param string           $type     The type
     * @param class-string     $class    The class
     * @param non-empty-string $property The property
     *
     * @return Annotation[]
     */
    public function propertyAnnotationsByType(string $type, string $class, string $property): array;

    /**
     * Get a class's properties' annotations by type.
     *
     * @param string       $type  The type
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function propertiesAnnotationsByType(string $type, string $class): array;

    /**
     * Get a method's annotations by type.
     *
     * @param string           $type   The type
     * @param class-string     $class  The class
     * @param non-empty-string $method The method
     *
     * @return Annotation[]
     */
    public function methodAnnotationsByType(string $type, string $class, string $method): array;

    /**
     * Get a class's methods' annotations by type.
     *
     * @param string       $type  The type
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function methodsAnnotationsByType(string $type, string $class): array;

    /**
     * Get a function's annotations.
     *
     * @param string          $type     The type
     * @param callable-string $function The function
     *
     * @return Annotation[]
     */
    public function functionAnnotationsByType(string $type, string $function): array;

    /**
     * Filter annotations by type.
     *
     * @param string     $type           The type to match
     * @param Annotation ...$annotations The annotations
     *
     * @return Annotation[]
     */
    public function filterAnnotationsByType(string $type, Annotation ...$annotations): array;

    /**
     * Filter annotations by types.
     *
     * @param array      $types          The types to match
     * @param Annotation ...$annotations The annotations
     *
     * @return Annotation[]
     */
    public function filterAnnotationsByTypes(array $types, Annotation ...$annotations): array;
}
