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

namespace Valkyrja\Attribute\Contract;

use Closure;

/**
 * Interface Attributes.
 *
 * @author Melech Mizrachi
 */
interface Attributes
{
    /**
     * Get a class' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClass(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' members' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClassMembers(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' and class' members' attributes.
     *
     * @template T
     *
     * @param class-string         $class     K The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClassAndMembers(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' constant's attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $constant  The constant
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forConstant(string $class, string $constant, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' constants' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forConstants(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a property's attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $property  The property
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forProperty(string $class, string $property, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' properties' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forProperties(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a method's attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethod(string $class, string $method, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a method's parameters' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethodParameters(string $class, string $method, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a method's parameters' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param non-empty-string     $parameter The parameter
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethodParameter(string $class, string $method, string $parameter, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a class' methods' attributes.
     *
     * @template T
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethods(string $class, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a function's attributes.
     *
     * @template T
     *
     * @param callable-string      $function  The function
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forFunction(string $function, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a function's parameters' attributes.
     *
     * @template T
     *
     * @param callable-string      $function  The function
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forFunctionParameters(string $function, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a closure's attributes.
     *
     * @template T
     *
     * @param Closure              $closure   The closure
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClosure(Closure $closure, ?string $attribute = null, ?int $flags = null): array;

    /**
     * Get a closure's parameters' attributes.
     *
     * @template T
     *
     * @param Closure              $closure   The closure
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClosureParameters(Closure $closure, ?string $attribute = null, ?int $flags = null): array;
}
