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
use Valkyrja\ORM\ORM;
use Valkyrja\Support\Model\Traits\ModelTrait;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;
use Valkyrja\Support\Type\Str;

use function is_string;
use function serialize;
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
    public static function getFieldCastings(): array
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
    public static function getCastingAllowedClasses(): array
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
     * Get a list of hidden fields we can expose for storage.
     *
     * @return string[]
     */
    public static function getStorableHiddenFields(): array
    {
        return [];
    }

    /**
     * Set a relationship property.
     *
     * @param ORM    $orm          The ORM
     * @param string $relationship The relationship to set
     *
     * @return void
     */
    public function __setRelationship(ORM $orm, string $relationship): void
    {
        $methodName = 'set' . Str::toStudlyCase($relationship) . 'Relationship';

        if (method_exists($this, $methodName)) {
            $this->$methodName($orm);

            return;
        }
    }

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @throws JsonException
     *
     * @return array
     */
    public function __storable(string ...$properties): array
    {
        return $this->__toArrayOrStorable(true, ...$properties);
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
    public function __setProperties(array $properties): void
    {
        $propertyTypes          = static::getFieldCastings();
        $propertyAllowedClasses = static::getCastingAllowedClasses();

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                if (! isset($this->__originalProperties[$property])) {
                    $this->__originalProperties[$property] = $value;
                }

                // Set the property
                $this->__set(
                    $property,
                    $this->__getPropertyValueByType(
                        $propertyTypes,
                        $propertyAllowedClasses,
                        $property,
                        $value
                    )
                );
            }
        }
    }

    /**
     * Get model as an array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @throws JsonException
     *
     * @return array
     */
    public function __toArray(string ...$properties): array
    {
        return $this->__toArrayOrStorable(false, ...$properties);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    protected function __asArrayForChangedComparison(): array
    {
        return $this->__storable();
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
    protected function __getPropertyValueForDataStore(array $propertyTypes, string $property)
    {
        $value = $this->__get($property);
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        return match ($type) {
            PropertyType::OBJECT => serialize($value),
            PropertyType::ARRAY => Arr::toString($value),
            PropertyType::JSON => Obj::toString($value),
            PropertyType::STRING => (string) $value,
            PropertyType::INT => (int) $value,
            PropertyType::FLOAT => (float) $value,
        };
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
     *
     * @return mixed
     */
    protected function __getPropertyValueByType(
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

        return match ($type) {
            PropertyType::OBJECT => unserialize(
                $value,
                [
                    'allowed_classes' => $propertyAllowedClasses[$property] ?? [],
                ]
            ),
            PropertyType::ARRAY => Arr::fromString($value),
            PropertyType::JSON => Obj::fromString($value),
            PropertyType::STRING => (string) $value,
            PropertyType::INT => (int) $value,
            PropertyType::FLOAT => (float) $value,
        };
    }

    /**
     * Convert the entity to an array or storable array.
     *
     * @param bool   $storable      [optional] Whether to get as a storable array.
     * @param string ...$properties [optional] An array of properties to return
     *
     * @throws JsonException
     *
     * @return array
     */
    protected function __toArrayOrStorable(bool $storable = false, string ...$properties): array
    {
        $storableHiddenFields   = $storable ? static::getStorableHiddenFields() : [];
        $allProperties          = array_merge(Obj::getProperties($this), $this->__exposed);
        $propertyTypes          = static::getFieldCastings();
        $relationshipProperties = static::getRelationshipProperties();

        // Iterate through all the storable hidden fields
        foreach ($storableHiddenFields as $storableHiddenField) {
            // Add the storable field to the all properties array
            $allProperties[$storableHiddenField] = true;
        }

        // If a list of properties was specified
        if (! empty($properties)) {
            // Let's only return those properties
            $allProperties = $this->__onlyProperties($allProperties, $properties);
        }

        // Iterate through the properties to return
        foreach ($allProperties as $property => $value) {
            // If this property is a relationship and we're going for storage
            if ($storable && isset($relationshipProperties[$property])) {
                // Skip it
                continue;
            }

            // Get the value
            $this->__setPropertyInArray($allProperties, $propertyTypes, $property, $storable);
        }

        return $allProperties;
    }

    /**
     * Set a property in an array.
     *
     * @param array  $properties    The properties array to set into
     * @param array  $propertyTypes The property types
     * @param string $property      The property
     * @param bool   $storable      [optional] Whether to get as a storable array.
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function __setPropertyInArray(
        array &$properties,
        array $propertyTypes,
        string $property,
        bool $storable = false
    ): void {
        // If this is a storable array we're building
        if ($storable) {
            // Get the value for the data store
            $properties[$property] = $this->__getPropertyValueForDataStore($propertyTypes, $property);

            return;
        }

        $properties[$property] = $this->__get($property);
    }
}
