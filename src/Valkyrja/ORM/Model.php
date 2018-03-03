<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use JsonSerializable;
use Valkyrja\ORM\Enums\PropertyType;

/**
 * Class Model.
 *
 * @author Melech Mizrachi
 */
abstract class Model implements JsonSerializable
{
    /**
     * The table name.
     *
     * @var string
     */
    protected static $table = self::class;

    /**
     * Valid types allowed to be mass set.
     *
     * @var array
     */
    protected static $properties = [];

    /**
     * Types for attributes that differs from what they were saved into the database as.
     *
     * @var array
     */
    protected static $propertyTypes = [];

    /**
     * The ORM repository to use.
     *
     * @var string|null
     */
    protected static $repository;

    /**
     * Get the table.
     *
     * @return string
     */
    public static function getTable(): string
    {
        return static::$table;
    }

    /**
     * Get the attributes.
     *
     * @return array
     */
    public static function getProperties(): array
    {
        return static::$properties;
    }

    /**
     * Get the attribute types.
     *
     * @return array
     */
    public static function getPropertyTypes(): array
    {
        return static::$propertyTypes;
    }

    /**
     * Get the ORM repository.
     *
     * @return null|string
     */
    public static function getRepository(): ? string
    {
        return static::$repository;
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
    public function fromArray(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // If the value is null or the property doesn't exist in this model
            if (null === $value || ! property_exists($this, $property)) {
                // Continue to the next property
                continue;
            }

            // Check if a type was set for this attribute
            $type = static::$propertyTypes[$property] ?? null;

            // If the type is object and the property isn't already an object
            if ($type === PropertyType::OBJECT && ! \is_object($value)) {
                // Unserialize the object
                $value = unserialize($value, true);
            } // If the type is array and the property isn't already an array
            elseif ($type === PropertyType::ARRAY && ! \is_array($value)) {
                $value = json_decode($value);
            }

            // Set the property
            $this->__set($property, $value);
        }
    }

    /**
     * Get model as an array.
     *
     * @param bool $all  [optional] Whether to include all properties or only those defined in the static properties
     *                   array
     * @param bool $safe [optional] True only includes public/protected while false will include private when using
     *                   false for $all
     *
     * @return array
     */
    public function asArray(bool $all = true, bool $safe = true): array
    {
        // All the public and protected properties
        $safeProperties = get_object_vars($this);

        // If all is true
        if ($all) {
            // Get all the object vars
            return $safeProperties;
        }

        $properties = [];

        // Otherwise iterate through the properties array
        foreach (static::$properties as $property) {
            // If only public and protected properties should be included and this property doesn't exist in the full
            // list then it is private
            if ($safe && empty($safeProperties[$property])) {
                // So continue to the next property
                continue;
            }

            // And set each property to its value
            $properties[$property] = $this->{$property};
        }

        return $properties;
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
