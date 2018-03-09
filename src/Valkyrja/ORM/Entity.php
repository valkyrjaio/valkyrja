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

use Valkyrja\Model\Model;
use Valkyrja\ORM\Enums\PropertyType;

/**
 * Class Entity.
 *
 * @author Melech Mizrachi
 */
abstract class Entity extends Model
{
    /**
     * The table name.
     *
     * @var string
     */
    protected static $table = self::class;

    /**
     * The id field.
     *
     * @var string
     */
    protected static $idField = 'id';

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
     * Entity constructor.
     *
     * @param array $properties [optional] The properties
     */
    public function __construct(array $properties = [])
    {
        $this->fromArray($properties);
    }

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
     * Get the id field.
     *
     * @return string
     */
    public static function getIdField(): string
    {
        return self::$idField;
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
            } // Otherwise if a type was set and the value isn't already of that type
            elseif ($type !== null && ! ($value instanceof $type)) {
                // Create it
                $value = new $type($value);
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

            $value = $this->{$property};
            // Check if a type was set for this attribute
            $type = static::$propertyTypes[$property] ?? null;

            // If the type is object and the property isn't already an object
            if ($type === PropertyType::OBJECT && \is_object($value)) {
                // Unserialize the object
                $value = unserialize($value, true);
            } // If the type is array and the property isn't already an array
            elseif ($type === PropertyType::ARRAY && \is_array($value)) {
                $value = json_decode($value);
            } // If the value is another entity
            elseif ($value instanceof self) {
                $value = $value->asArray();
            }

            // And set each property to its value
            $properties[$property] = $value;
        }

        return $properties;
    }
}
