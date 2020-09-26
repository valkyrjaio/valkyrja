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

namespace Valkyrja\ORM\Entities;

use JsonException;
use Valkyrja\ORM\Constants\PropertyType;
use Valkyrja\Support\Model\Traits\ModelTrait;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;
use Valkyrja\Support\Type\Str;

use function in_array;
use function is_string;
use function method_exists;
use function serialize;
use function ucwords;
use function unserialize;

/**
 * Trait EntityTrait.
 *
 * @author Melech Mizrachi
 */
trait EntityTrait
{
    use ModelTrait;

    /**
     * Get the table.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return static::class;
    }

    /**
     * Get the id field.
     *
     * @return string
     */
    public static function getIdField(): string
    {
        return 'id';
    }

    /**
     * Get the repository to use for this entity.
     *
     * @return string|null
     */
    public static function getEntityRepository(): ?string
    {
        return null;
    }

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
     * @return array
     */
    public static function getPropertyTypes(): array
    {
        return [];
    }

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
     * @return array
     */
    public static function getPropertyAllowedClasses(): array
    {
        return [];
    }

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
     * @return array
     */
    public static function getRelationshipProperties(): array
    {
        return [];
    }

    /**
     * Get the id field value.
     *
     * @return string
     */
    public function getIdFieldValue(): string
    {
        return (string) $this->{static::getIdField()};
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
        $this->{static::getIdField()} = $id;
    }

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @throws JsonException
     *
     * @return array
     */
    public function forDataStore(): array
    {
        $properties             = [];
        $propertyTypes          = static::getPropertyTypes();
        $relationshipProperties = static::getRelationshipProperties();

        // Otherwise iterate through the properties array
        foreach ($this->_getPropertyNames() as $property) {
            if (isset($relationshipProperties[$property])) {
                continue;
            }

            $properties[$property] = $this->getPropertyValueForDataStore($propertyTypes, $property);
        }

        return $properties;
    }

    /**
     * Get all the relations for the entity as defined in getPropertyTypes and getPropertyMapper.
     *
     * @param array|null $relationships [optional] The relationships to get (null will get all relationships)
     *
     * @return void
     */
    public function withRelationships(array $relationships = null): void
    {
        // Iterate through the property types
        foreach (static::getRelationshipProperties() as $property) {
            if (null !== $relationships && ! in_array($property, $relationships, true)) {
                continue;
            }

            $methodName = 'set' . ucwords(Str::toStudlyCase($property)) . 'Relationship';

            if (method_exists($this, $methodName)) {
                $this->$methodName();
            }
        }
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties
     *
     * @throws JsonException
     *
     * @return void
     */
    public function _setProperties(array $properties): void
    {
        $propertyTypes          = static::getPropertyTypes();
        $propertyAllowedClasses = static::getPropertyAllowedClasses();

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            // Set the property
            $this->{$property} = $this->getPropertyValueByType(
                $propertyTypes,
                $propertyAllowedClasses,
                $property,
                $value
            );
        }
    }

    /**
     * Get a property's value for data store.
     *
     * @param array  $propertyTypes The property types
     * @param string $property      The property name
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function getPropertyValueForDataStore(array $propertyTypes, string $property)
    {
        $value = $this->{$property};
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        // If the type is object
        if ($type === PropertyType::OBJECT) {
            // Unserialize the object
            $value = serialize($value);
        } // If the type is array
        elseif ($type === PropertyType::ARRAY) {
            $value = Arr::toString($value);
        } // If the type is json
        elseif ($type === PropertyType::JSON) {
            $value = Obj::toString($value);
        }

        return $value;
    }

    /**
     * Get a property's value by the type (if type is set).
     *
     * @param array  $propertyTypes          The property types
     * @param array  $propertyAllowedClasses The property allowed classes
     * @param string $property               The property name
     * @param mixed  $value                  The property value
     *
     * @throws JsonException
     * @return mixed
     */
    protected function getPropertyValueByType(
        array $propertyTypes,
        array $propertyAllowedClasses,
        string $property,
        $value
    ) {
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type || ! is_string($value)) {
            return $value;
        }

        // If the type is object
        if ($type === PropertyType::OBJECT) {
            // Unserialize the object
            $value = unserialize($value, ['allowed_classes' => $propertyAllowedClasses[$property] ?? []]);
        } // If the type is array
        elseif ($type === PropertyType::ARRAY) {
            // Create a new array from the json string
            $value = Arr::fromString($value);
        } // If the type is json
        elseif ($type === PropertyType::JSON) {
            // Create a new object from the json string
            $value = Obj::fromString($value);
        }

        return $value;
    }
}
