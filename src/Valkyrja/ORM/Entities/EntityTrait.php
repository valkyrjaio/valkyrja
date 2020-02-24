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
use Valkyrja\ORM\Enums\PropertyMap;
use Valkyrja\ORM\Enums\PropertyType;
use Valkyrja\ORM\Repositories\Repository;

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
     * @var string
     */
    protected static string $repository = Repository::class;

    /**
     * Valid types allowed to be mass set.
     *  NOTE: Set this if you'd like to save a array_keys(get_object_vars()) call later.
     *
     * @var array
     */
    protected static array $properties = [];

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
     * Allowed classes for serialization of object type properties.
     * <code>
     *      [
     *          // An array of allowed classes for serialization for object types
     *          'property_name' => [ClassName::class],
     *      ]
     * </code>.
     *
     * @var array
     */
    protected static array $propertyAllowedClasses = [];

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
     * Get the ORM repository.
     *
     * @return string
     */
    public static function getRepository(): string
    {
        return static::$repository;
    }

    /**
     * Get the properties.
     *
     * @return string[]
     */
    public function getProperties(): array
    {
        if (empty(self::$properties)) {
            self::$properties = array_keys(get_object_vars($this));
        }

        return self::$properties;
    }

    /**
     * Get the property types.
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
     * @return array
     */
    public static function getPropertyTypes(): array
    {
        return static::$propertyTypes;
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
     *              'foreign_column_relation'  => $this->field,
     *              PropertyMap::ORDER_BY      => 'some_column',
     *              PropertyMap::LIMIT         => 1,
     *              PropertyMap::OFFSET        => 0,
     *              PropertyMap::COLUMNS       => [],
     *              PropertyMap::GET_RELATIONS => true | false,
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
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    public function forDataStore(): array
    {
        $properties = [];

        // Otherwise iterate through the properties array
        foreach ($this->getProperties() as $property) {
            $this->getPropertyValueForDataStore($property);
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
    public function setRelations(array $columns = null): void
    {
        $propertyTypes  = $this::getPropertyTypes();
        $propertyMapper = $this->getPropertyMapper();

        // Iterate through the property types
        foreach ($propertyTypes as $property => $type) {
            if (null !== $columns && ! in_array($property, $columns, true)) {
                continue;
            }

            $this->setEntityRelation($propertyMapper, $property, $type);
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
     * Set a relation.
     *
     * @param array  $propertyMapper
     * @param string $property
     * @param        $type
     *
     * @return void
     */
    protected function setEntityRelation(array $propertyMapper, string $property, $type): void
    {
        $entityName  = is_array($type) ? $type[0] : $type;
        $propertyMap = $propertyMapper[$property] ?? null;

        if (null !== $propertyMap && (is_array($type) || ! PropertyType::isValid($type))) {
            $entities = $this->getRelationEntities($entityName, $propertyMap);

            if (is_array($type)) {
                $this->{$property} = $entities;
                $this->__set($property, $entities);

                return;
            }

            if (empty($entities)) {
                return;
            }

            $this->__set($property, $entities[0]);
        }
    }

    /**
     * Get relationship's entities.
     *
     * @param string $entityName
     * @param array  $propertyMap
     *
     * @return array
     */
    protected function getRelationEntities(string $entityName, array $propertyMap): array
    {
        $repository   = entityManager()->repository($entityName);
        $orderBy      = $propertyMap[PropertyMap::ORDER_BY] ?? null;
        $limit        = $propertyMap[PropertyMap::LIMIT] ?? null;
        $offset       = $propertyMap[PropertyMap::OFFSET] ?? null;
        $columns      = $propertyMap[PropertyMap::COLUMNS] ?? null;
        $getRelations = $propertyMap[PropertyMap::GET_RELATIONS] ?? true;

        unset(
            $propertyMap[PropertyMap::ORDER_BY],
            $propertyMap[PropertyMap::LIMIT],
            $propertyMap[PropertyMap::OFFSET],
            $propertyMap[PropertyMap::COLUMNS],
            $propertyMap[PropertyMap::GET_RELATIONS]
        );

        return $repository->findAllBy($propertyMap, $orderBy, $limit, $offset, $columns, $getRelations);
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @return void
     */
    public function setPropertiesFromArray(array $properties): void
    {
        // Iterate through the properties
        foreach ($properties as $property => $value) {
            $this->setEntityProperty($property, $value);
        }
    }

    /**
     * Set a property.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return void
     */
    protected function setEntityProperty(string $property, $value): void
    {
        // If the value is null or the property doesn't exist in this model
        if (null === $value || ! property_exists($this, $property)) {
            // Continue to the next property
            return;
        }

        // Set the property
        $this->{$property} = $this->getPropertyValueByType($property, $value);
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

        switch ($type) {
            // If the type is object and the property isn't already an object
            case PropertyType::OBJECT:
                if (! is_object($value)) {
                    $value = unserialize($value, static::$propertyAllowedClasses[$property] ?? null);
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

        return $value;
    }
}
