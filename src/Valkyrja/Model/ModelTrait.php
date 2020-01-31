<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Model;

/**
 * Trait ModelTrait.
 *
 * @author Melech Mizrachi
 */
trait ModelTrait
{
    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return static|Model
     */
    public static function fromArray(array $properties): Model
    {
        $model = new static();

        $model->setProperties($properties);

        return $model;
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
    public function setProperties(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // If the value is null or the property doesn't exist in this model
            if (null === $value || ! property_exists($this, $property)) {
                // Continue to the next property
                continue;
            }

            // Set the property
            $this->{$property} = $value;
        }
    }

    /**
     * Get model as an array.
     *
     * @return array
     */
    public function asArray(): array
    {
        return $this->jsonSerialize();
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
