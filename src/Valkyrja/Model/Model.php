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

namespace Valkyrja\Model;

use JsonSerializable;

/**
 * Interface Model.
 *
 * @author Melech Mizrachi
 */
interface Model extends JsonSerializable
{
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
     * Determine whether the model has a property.
     *
     * @param string $property The property
     *
     * @return bool
     */
    public function hasProperty(string $property): bool;

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
}
