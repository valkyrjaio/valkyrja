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

namespace Valkyrja\Annotation\Contract;

use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Annotation\Parser\Contract\Parser;

/**
 * Interface Annotations.
 *
 * @author Melech Mizrachi
 */
interface Annotations
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
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function forClass(string $class): array;

    /**
     * Get a class's members' annotations.
     *
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function forClassMembers(string $class): array;

    /**
     * Get a class's and class's members' annotations.
     *
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function forClassAndMembers(string $class): array;

    /**
     * Get a property's annotations.
     *
     * @param class-string     $class    The class
     * @param non-empty-string $property The property
     *
     * @return Annotation[]
     */
    public function forClassProperty(string $class, string $property): array;

    /**
     * Get a class's properties' annotations.
     *
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function forClassProperties(string $class): array;

    /**
     * Get a method's annotations.
     *
     * @param class-string     $class  The class
     * @param non-empty-string $method The method
     *
     * @return Annotation[]
     */
    public function forClassMethod(string $class, string $method): array;

    /**
     * Get a class's methods' annotations.
     *
     * @param class-string $class The class
     *
     * @return Annotation[]
     */
    public function forClassMethods(string $class): array;

    /**
     * Get a function's annotations.
     *
     * @param callable-string $function The function
     *
     * @return Annotation[]
     */
    public function forFunction(string $function): array;
}
