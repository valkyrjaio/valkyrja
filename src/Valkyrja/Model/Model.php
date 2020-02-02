<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Model;

use JsonSerializable;

/**
 * Class Model.
 *
 * @author Melech Mizrachi
 */
abstract class Model implements JsonSerializable
{
    /**
     * Model constructor.
     *
     * @param array $properties [optional] The properties
     */
    public function __construct(array $properties = [])
    {
        $this->fromArray($properties);
    }

    /**
     * Get a property.
     *
     * @param string $name The property to get
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'get' . $methodName;

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return $this->{$name};
    }

    /**
     * Set a property.
     *
     * @param string $name  The property to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'set' . $methodName;

        if (method_exists($this, $methodName)) {
            $this->$methodName($value);

            return;
        }

        $this->{$name} = $value;
    }

    /**
     * Check if a property is set.
     *
     * @param string $name The property to check
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        $methodName = str_replace('_', '', ucwords($name, '_'));
        $methodName = 'isset' . $methodName;

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return property_exists($this, $name);
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function fromArray(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // If the value is null or the property doesn't exist in this model
            if (null === $value || ! property_exists($this, $property)) {
                // Continue to the next property
                continue;
            }

            // Set the property
            $this->__set($property, $value);
        }
    }

    /**
     * Serialize properties for json_encode.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
