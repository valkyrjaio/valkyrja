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

/**
 * Interface Annotator.
 *
 * @author Melech Mizrachi
 */
interface Annotator
{
    /**
     * Get the parser.
     *
     * @return Parser
     */
    public function getParser(): Parser;

    /**
     * Set the parser.
     *
     * @param Parser $parser The parser
     *
     * @return void
     */
    public function setParser(Parser $parser): void;

    /**
     * Get a class's annotations.
     *
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classAnnotations(string $class): array;

    /**
     * Get a class's members' annotations.
     *
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classMembersAnnotations(string $class): array;

    /**
     * Get a class's and class's members' annotations.
     *
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function classAndMembersAnnotations(string $class): array;

    /**
     * Get a property's annotations.
     *
     * @param string $class    The class
     * @param string $property The property
     *
     * @return Annotation[]
     */
    public function propertyAnnotations(string $class, string $property): array;

    /**
     * Get a class's properties' annotations.
     *
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function propertiesAnnotations(string $class): array;

    /**
     * Get a method's annotations.
     *
     * @param string $class  The class
     * @param string $method The method
     *
     * @return Annotation[]
     */
    public function methodAnnotations(string $class, string $method): array;

    /**
     * Get a class's methods' annotations.
     *
     * @param string $class The class
     *
     * @return Annotation[]
     */
    public function methodsAnnotations(string $class): array;

    /**
     * Get a function's annotations.
     *
     * @param string $function The function
     *
     * @return Annotation[]
     */
    public function functionAnnotations(string $function): array;
}
