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

namespace Valkyrja\Attribute\Collector\Contract;

use Closure;

/**
 * Interface CollectorContract.
 *
 * @author Melech Mizrachi
 */
interface CollectorContract
{
    /**
     * Get a class' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forClass(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' members' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forClassMembers(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' and class' members' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     K The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forClassAndMembers(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' constant's attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $constant  The constant
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forConstant(string $class, string $constant, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' constants' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forConstants(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a property's attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $property  The property
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forProperty(string $class, string $property, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' properties' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forProperties(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a method's attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forMethod(string $class, string $method, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a method's parameters' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forMethodParameters(string $class, string $method, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a method's parameters' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param non-empty-string     $method    The method
     * @param non-empty-string     $parameter The parameter
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forMethodParameter(string $class, string $method, string $parameter, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a class' methods' attributes.
     *
     * @template T of object
     *
     * @param class-string         $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forMethods(string $class, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a function's attributes.
     *
     * @template T of object
     *
     * @param callable-string      $function  The function
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forFunction(string $function, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a function's parameters' attributes.
     *
     * @template T of object
     *
     * @param callable-string      $function  The function
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forFunctionParameters(string $function, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a closure's attributes.
     *
     * @template T of object
     *
     * @param Closure              $closure   The closure
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forClosure(Closure $closure, string|null $attribute = null, int|null $flags = null): array;

    /**
     * Get a closure's parameters' attributes.
     *
     * @template T of object
     *
     * @param Closure              $closure   The closure
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return ($attribute is class-string<T> ? T[] : object[])
     */
    public function forClosureParameters(Closure $closure, string|null $attribute = null, int|null $flags = null): array;
}
