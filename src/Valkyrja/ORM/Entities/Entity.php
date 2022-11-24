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
use Valkyrja\ORM\Entity as EntityContract;
use Valkyrja\Support\Model\Classes\Model;
use Valkyrja\Support\Model\Constants\PropertyType;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

use function in_array;
use function is_string;

/**
 * Class Entity.
 *
 * @author Melech Mizrachi
 */
abstract class Entity extends Model implements EntityContract
{
    /**
     * The table name.
     *
     * @var string
     */
    protected static string $tableName;

    /**
     * The id field.
     *
     * @var string
     */
    protected static string $idField = 'id';

    /**
     * The repository.
     *
     * @var string|null
     */
    protected static ?string $repository = null;

    /**
     * A list of hidden fields we can expose for storage.
     *
     * @var string[]
     */
    protected static array $relationshipProperties = [];

    /**
     * A list of hidden fields we can expose for storage.
     *
     * @var string[]
     */
    protected static array $storableHiddenFields = [];

    /**
     * The connection to use.
     *
     * @var string|null
     */
    protected static ?string $connection = null;

    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * @inheritDoc
     */
    public static function getIdField(): string
    {
        return static::$idField;
    }

    /**
     * @inheritDoc
     */
    public static function getRepository(): ?string
    {
        return static::$repository;
    }

    /**
     * @inheritDoc
     */
    public static function getConnection(): ?string
    {
        return static::$connection;
    }

    /**
     * @inheritDoc
     */
    public static function getRelationshipProperties(): array
    {
        return static::$relationshipProperties;
    }

    /**
     * @inheritDoc
     */
    public static function getStorableHiddenFields(): array
    {
        return static::$storableHiddenFields;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asStorableArray(string ...$properties): array
    {
        return $this->__toArrayOrStorable(true, ...$properties);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asArray(string ...$properties): array
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
        return $this->asStorableArray();
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
    protected function __getPropertyValueForDataStore(array $propertyTypes, string $property): mixed
    {
        $value = $this->__get($property);
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        switch ($type) {
            case PropertyType::OBJECT :
                if (! is_string($value)) {
                    $value = serialize($value);
                }

                break;
            case PropertyType::ARRAY :
                if (! is_string($value)) {
                    $value = Arr::toString($value);
                }

                break;
            case PropertyType::JSON :
                if (! is_string($value)) {
                    $value = Obj::toString($value);
                }

                break;
            case PropertyType::STRING :
                $value = (string) $value;

                break;
            case PropertyType::INT :
                $value = (int) $value;

                break;
            case PropertyType::FLOAT :
                $value = (float) $value;

                break;
            case PropertyType::BOOL :
                $value = (bool) $value;

                break;
            default :
                if ($value instanceof \Valkyrja\Support\Model\Model) {
                    $value = $value->__toString();
                }

                break;
        }

        return $value;
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
        $propertyTypes          = static::getCastings();
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
            if ($storable && in_array($property, $relationshipProperties, true)) {
                unset($allProperties[$property]);

                continue;
            }

            // Get the value
            $this->__setPropertyInArray($allProperties, $propertyTypes, $property, $storable);
        }

        unset($allProperties['__exposed'], $allProperties['__originalProperties']);

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
