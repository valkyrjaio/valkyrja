<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM\Entities;

use Valkyrja\Model\ModelTrait;
use Valkyrja\ORM\Enums\PropertyType;

use function in_array;
use function is_array;
use function is_object;

use const JSON_THROW_ON_ERROR;

/**
 * Trait EntityTrait.
 *
 * @author Melech Mizrachi
 */
trait EntityTrait
{
    use ModelTrait;

    /**
     * The table name.
     *
     * @var string
     */
    protected static string $table = self::class;

    /**
     * The id field.
     *
     * @var string
     */
    protected static string $idField = 'id';

    /**
     * The ORM repository to use.
     *
     * @var string|null
     */
    protected static ?string $repository = null;

    /**
     * Types for attributes that differs from what they were saved into the database as.
     *
     * <code>
     *      [
     *          // An array to be json_encoded/decoded to/from the db
     *          'property_name' => 'array',
     *          // An object to be serialized and unserialized to/from the db
     *          'property_name' => 'object',
     *      ]
     * </code>
     *
     * @var array
     */
    protected static array $propertyTypes = [];

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
     * @var array
     */
    protected static array $propertyAllowedClasses = [];

    /**
     * Entity relationship properties.
     *
     * <code>
     *      [
     *          'property_name',
     *          'property_name_alt',
     *          ...
     *      ]
     * </code>
     *
     * @var array
     */
    protected static array $relationshipProperties = [];

    /**
     * Get the table.
     *
     * @return string
     */
    public static function getEntityTable(): string
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
        return static::$idField;
    }

    /**
     * Get the repository to use for this entity.
     *
     * @return string|null
     */
    public static function getEntityRepository(): ?string
    {
        return static::$repository;
    }

    /**
     * Get the id field value.
     *
     * @return string
     */
    public function getIdFieldValue(): string
    {
        return (string) $this->{static::$idField};
    }

    /**
     * Set the id field value.
     *
     * @param string $id
     *
     * @return void
     */
    public function setIdFieldValue(string $id): void
    {
        $this->{static::$idField} = $id;
    }

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    public function forDataStore(): array
    {
        $properties = [];

        // Otherwise iterate through the properties array
        foreach ($this->getModelProperties() as $property) {
            if (isset(static::$relationshipProperties[$property])) {
                continue;
            }

            $properties[$property] = $this->getPropertyValueForDataStore($property);
        }

        return $properties;
    }

    /**
     * Get all the relations for the entity as defined in getPropertyTypes and getPropertyMapper.
     *
     * @param array|null $columns
     *
     * @return void
     */
    public function setEntityRelations(array $columns = null): void
    {
        // Iterate through the property types
        foreach (static::$relationshipProperties as $property) {
            if (null !== $columns && ! in_array($property, $columns, true)) {
                continue;
            }

            $this->__get($property);
        }
    }

    /**
     * Get a property's value for data store.
     *
     * @param string $property
     *
     * @return mixed
     */
    protected function getPropertyValueForDataStore(string $property)
    {
        $value = $this->{$property};
        // Check if a type was set for this attribute
        $type = static::$propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        // If the type is object and the property isn't already an object
        if ($type === PropertyType::OBJECT && is_object($value)) {
            // Unserialize the object
            $value = serialize($value);
        } // If the type is array and the property isn't already an array
        elseif ($type === PropertyType::ARRAY && is_array($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        return $value;
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function setModelProperties(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // Set the property
            $this->{$property} = $this->getPropertyValueByType($property, $value);
        }
    }

    /**
     * Get a property's value by the type (if type is set).
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function getPropertyValueByType(string $property, $value)
    {
        // Check if a type was set for this attribute
        $type = static::$propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        // If the type is object and the property isn't already an object
        if ($type === PropertyType::OBJECT && ! is_object($value)) {
            // Unserialize the object
            $value = unserialize($value, ['allowed_classes' => static::$propertyAllowedClasses[$property] ?? []]);
        } // If the type is array and the property isn't already an array
        elseif ($type === PropertyType::ARRAY && ! is_array($value)) {
            $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        return $value;
    }
}
