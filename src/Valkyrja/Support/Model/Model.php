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

namespace Valkyrja\Support\Model;

use JsonSerializable;
use Valkyrja\Support\Model\Enums\CastType;

/**
 * Interface Model.
 *
 * @author Melech Mizrachi
 */
interface Model extends JsonSerializable
{
    /**
     * Get a list of exposable properties.
     *
     * @return string[]
     */
    public static function getExposable(): array;

    /**
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // An property to be json_decoded to an array
     *          'property_name' => CastType::array,
     *          // An property to be unserialized to an object
     *          'property_name' => CastType::object,
     *          // An property to be json_decoded to an object
     *          'property_name' => CastType::json,
     *          // An property to be cast to an string
     *          'property_name' => CastType::string,
     *          // An property to be cast to an int
     *          'property_name' => CastType::int,
     *          // An property to be cast to an float
     *          'property_name' => CastType::float,
     *          // An property to be cast to an bool
     *          'property_name' => CastType::bool,
     *          // An property to be cast to an enum
     *          'property_name' => [CastType::enum, Enum::class],
     *          // An property to be cast to a model
     *          'property_name' => [CastType::model, Model::class],
     *          // An property to be cast to an array of models
     *          'property_name' => [CastType::model, [Model::class]],
     *      ]
     * </code>
     *
     * @return array<string, CastType|array<CastType, string|string[]>>
     */
    public static function getCastings(): array;

    /**
     * Allowed classes for serialization of object type properties.
     *
     * <code>
     *      [
     *          // An array of allowed classes for serialization for object types
     *          'property_name' => [ClassName::class],
     *      ]
     * </code>
     *
     * @return array<string, string[]>
     */
    public static function getCastingsAllowedClasses(): array;

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties The properties
     *
     * @return static
     */
    public static function fromArray(array $properties): static;

    /**
     * Get a property.
     *
     * @param string $name The property to get
     *
     * @return mixed
     */
    public function __get(string $name);

    /**
     * Set a property.
     *
     * @param string $name  The property to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function __set(string $name, mixed $value): void;

    /**
     * Check if a property is set.
     *
     * @param string $name The property to check
     *
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties The properties
     *
     * @return void
     */
    public function updateProperties(array $properties): void;

    /**
     * Get a new model with new properties.
     *
     * @param array $properties The properties to modify
     *
     * @return static
     */
    public function withProperties(array $properties): static;

    /**
     * Get model as an array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function asArray(string ...$properties): array;

    /**
     * Get model as an array with exposable properties.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function asArrayWithExposable(string ...$properties): array;

    /**
     * Get model as an array including only changed properties.
     *
     * @return array
     */
    public function asChangedArray(): array;

    /**
     * Get an original property value by name.
     *
     * @param string $name The original property to get
     *
     * @return mixed
     */
    public function getOriginalPropertyValue(string $name): mixed;

    /**
     * Get all original properties.
     *
     * @return array
     */
    public function asOriginalArray(): array;

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * To string.
     *
     * @return string
     */
    public function __toString(): string;

    /**
     * Expose hidden properties or all properties.
     *
     * @param string ...$properties The properties to expose
     *
     * @return void
     */
    public function expose(string ...$properties): void;

    /**
     * Unexpose hidden properties or all properties.
     *
     * @param string ...$properties [optional] The properties to unexpose
     *
     * @return void
     */
    public function unexpose(string ...$properties): void;
}
