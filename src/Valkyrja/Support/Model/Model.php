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
     * @param array $properties
     *
     * @return static
     */
    public static function fromArray(array $properties): self;

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
    public function __set(string $name, $value): void;

    /**
     * Check if a property is set.
     *
     * @param string $name The property to check
     *
     * @return bool
     */
    public function __isset(string $name): bool;

    /**
     * Get the properties.
     *
     * @return string[]
     */
    public function getModelProperties(): array;

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function setModelProperties(array $properties): void;

    /**
     * Get model as an array.
     *
     * @return array
     */
    public function asArray(): array;

    /**
     * Get model as a deep array where all properties are also arrays.
     *
     * @return array
     */
    public function asDeepArray(): array;

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
