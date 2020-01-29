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
    protected static string $table = self::class;

    /**
     * The id field.
     *
     * @var string
     */
    protected static string $idField = 'id';

    /**
     * Valid types allowed to be mass set.
     *
     * @var array
     */
    protected static array $properties = [];

    /**
     * Required properties.
     *
     * @var array
     */
    protected static array $required = [];

    /**
     * Types for attributes that differs from what they were saved into the database as.
     * <code>
     *      [
     *          // An array to be json_encoded/decoded to/from the db
     *          'property_name' => 'array',
     *          // An object to be serialized and unserialized to/from the db
     *          'property_name' => 'object',
     *          // A related entity
     *          'property_name' => Entity::class,
     *          // An array of related entities
     *          'property_name' => [Entity::class],
     *      ]
     * </code>.
     *
     * @var array
     */
    protected static array $propertyTypes = [];

    /**
     * The ORM repository to use.
     *
     * @var string|null
     */
    protected static ?string $repository;

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
     * Get the properties.
     *
     * @return array
     */
    public static function getProperties(): array
    {
        return static::$properties;
    }

    /**
     * Get the property types.
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
    public static function getRepository(): ?string
    {
        return static::$repository;
    }

    /**
     * Get required properties.
     *
     * @return array
     */
    public static function getRequiredProperties(): array
    {
        return static::$required;
    }

    /**
     * A mapper of property types to properties for generating a full entity with relations.
     * NOTE: Used in conjunction with Entity::$propertyTypes. If a property type is defined
     * but a property mapper is not, then the property type is NOT automatically filled in
     * via the EntityManager and Repository. If a mapper is specified and a type is not
     * then nothing happens.
     * <code>
     *      [
     *          'property_name' => [
     *              'field'         => 'fieldNameOfThisEntity',
     *              'relationField' => 'fieldNameOfTheRelationEntityToMapTo',
     *          ]
     *      ]
     * </code>.
     *
     * @return array
     */
    public function getPropertyMapper(): array
    {
        return [];
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return static
     */
    public static function fromArray(array $properties): self
    {
        $entity = new static();

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // If the value is null or the property doesn't exist in this model
            if (null === $value || ! property_exists($entity, $property)) {
                // Continue to the next property
                continue;
            }

            // Check if a type was set for this attribute
            $type = static::$propertyTypes[$property] ?? null;

            switch ($type) {
                // If the type is object and the property isn't already an object
                case PropertyType::OBJECT:
                    if (! is_object($value)) {
                        $value = unserialize($value, true);
                    }

                    break;
                // If the type is array and the property isn't already an array
                case PropertyType::ARRAY:
                    if (! is_array($value)) {
                        $value = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
                    }

                    break;
                default:
                    // Otherwise if a type was set and type is an array and the value is an array
                    // Then this should be an array of entities
                    if ($type !== null && is_array($type) && is_array($value)) {
                        // Iterate through the items
                        foreach ($value as &$item) {
                            // Create a new entity for each item
                            $item = new $type[0]($item);
                        }

                        // Unset the reference loop item
                        unset($item);
                    } // Otherwise if a type was set and the value isn't already of that type
                    elseif ($type !== null && ! ($value instanceof $type)) {
                        $value = new $type($value);
                    }
            }

            // Set the property
            $entity->{$property} = $value;
        }

        return $entity;
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
        foreach (static::$properties as $property) {
            $value = $this->{$property};
            // Check if a type was set for this attribute
            $type = static::$propertyTypes[$property] ?? null;

            // If the type is object and the property isn't already an object
            if ($type === PropertyType::OBJECT && is_object($value)) {
                // Unserialize the object
                $value = serialize($value);
            } // If the type is array and the property isn't already an array
            elseif ($type === PropertyType::ARRAY && is_array($value)) {
                $value = json_encode($value, JSON_THROW_ON_ERROR);
            }

            // And set each property to its value
            $properties[$property] = $value;
        }

        return $properties;
    }
}
