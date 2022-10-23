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

namespace Valkyrja\Attributes;

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
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClass(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' members' attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClassMembers(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' and class' members' attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forClassAndMembers(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' constant's attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param string               $const     The constant
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forConstant(string $class, string $const, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' constants' attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forConstants(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a property's attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param string               $property  The property
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forProperty(string $class, string $property, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' properties' attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forProperties(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a method's attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param string               $method    The method
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethod(string $class, string $method, string $attribute = null, int $flags = null): array;

    /**
     * Get a class' methods' attributes.
     *
     * @template T
     *
     * @param string               $class     The class
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forMethods(string $class, string $attribute = null, int $flags = null): array;

    /**
     * Get a function's attributes.
     *
     * @template T
     *
     * @param callable|string      $function  The function
     * @param class-string<T>|null $attribute [optional] The attribute to return
     *
     * @return object[]|T[]
     */
    public function forFunction(callable|string $function, string $attribute = null, int $flags = null): array;
}