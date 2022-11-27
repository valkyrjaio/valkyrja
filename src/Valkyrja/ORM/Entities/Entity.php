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

use BackedEnum;
use JsonException;
use Valkyrja\ORM\Entity as Contract;
use Valkyrja\Support\Model\Classes\Model;
use Valkyrja\Support\Model\Enums\CastType;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

use function is_array;
use function is_string;

/**
 * Class Entity.
 *
 * @author Melech Mizrachi
 */
abstract class Entity extends Model implements Contract
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
     * A list of fields we do not want to store.
     *
     * @var string[]
     */
    protected static array $unStorableFields = [];

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
    public static function getUnStorableFields(): array
    {
        return static::$unStorableFields;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asStorableArray(string ...$properties): array
    {
        return $this->__asStorableArray(true, ...$properties);
    }

    /**
     * @inheritDoc
     */
    protected function __asChangedArray(bool $toJson = true, bool $includeHidden = false): array
    {
        return parent::__asChangedArray($toJson);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    protected function __asArrayForChangedComparison(bool $toJson = true, bool $includeHidden = false): array
    {
        return $this->__asStorableArray($toJson);
    }

    /**
     * Convert the entity to an array or storable array.
     *
     * @param bool   $toJson        [optional] Whether to get as a json array
     * @param string ...$properties [optional] An array of properties to return
     *
     * @throws JsonException
     *
     * @return array
     */
    protected function __asStorableArray(bool $toJson = false, string ...$properties): array
    {
        $unStorableFields = array_merge(static::getUnStorableFields(), static::getRelationshipProperties());
        $allProperties    = $this->__asArray($toJson, true, ...$properties);
        $castings         = static::getCastings();

        // Iterate through all the un-storable fields
        foreach ($unStorableFields as $unStorableHiddenField) {
            // Remove the un-storable field to the all properties array
            unset($allProperties[$unStorableHiddenField]);
        }

        // Iterate through the properties to return
        foreach ($allProperties as $property => $value) {
            // Get the value
            $allProperties[$property] = $this->__getPropertyValueForDataStore($castings, $property);
        }

        return $allProperties;
    }

    /**
     * Get a property's value for data store.
     *
     * @param array  $castings The castings
     * @param string $property The property name
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getPropertyValueForDataStore(array $castings, string $property): mixed
    {
        $value = $this->__getAsArrayPropertyValue($castings, $property, true);
        // Check if a type was set for this attribute
        $type = $castings[$property] ?? null;

        // If there is no type specified just return the value
        if ($type === null || $value === null) {
            return $value;
        }

        return $this->__getPropertyValueForDataStoreMatch($type, $property, $value);
    }

    /**
     * Get a property's value by the type for a given type cast.
     *
     * @param CastType|array $type     The cast type
     * @param string         $property The property name
     * @param mixed          $value    The property value
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getPropertyValueForDataStoreMatch(CastType|array $type, string $property, mixed $value): mixed
    {
        return match ($this->__getTypeToCheck($type)) {
            CastType::string => $this->__getStringValueForDataStore($property, $value),
            CastType::int    => $this->__getIntValueForDataStore($property, $value),
            CastType::float  => $this->__getFloatValueForDataStore($property, $value),
            CastType::double => $this->__getDoubleValueForDataStore($property, $value),
            CastType::bool   => $this->__getBoolValueForDataStore($property, $value),
            CastType::model  => $this->__getModelValueForDataStore($property, $value),
            CastType::enum   => $this->__getEnumValueForDataStore($property, $value),
            CastType::array  => $this->__getArrayValueForDataStore($property, $value),
            CastType::json   => $this->__getJsonValueForDataStore($property, $value),
            CastType::object => $this->__getObjectValueForDataStore($property, $value),
            CastType::true   => $this->__getTrueValueForDataStore($property, $value),
            CastType::false  => $this->__getFalseValueForDataStore($property, $value),
            CastType::null   => $this->__getNullValueForDataStore($property, $value),
        };
    }

    /**
     * Get a string type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return string
     */
    protected function __getStringValueForDataStore(string $property, mixed $value): string
    {
        return (string) $value;
    }

    /**
     * Get an int type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return int
     */
    protected function __getIntValueForDataStore(string $property, mixed $value): int
    {
        return (int) $value;
    }

    /**
     * Get a float type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return float
     */
    protected function __getFloatValueForDataStore(string $property, mixed $value): float
    {
        return (float) $value;
    }

    /**
     * Get a double type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return double
     */
    protected function __getDoubleValueForDataStore(string $property, mixed $value): float
    {
        return (double) $value;
    }

    /**
     * Get a bool type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return bool
     */
    protected function __getBoolValueForDataStore(string $property, mixed $value): bool
    {
        return (bool) $value;
    }

    /**
     * Get an true type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return true
     */
    protected function __getTrueValueForDataStore(string $property, mixed $value): bool
    {
        return true;
    }

    /**
     * Get a false type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return false
     */
    protected function __getFalseValueForDataStore(string $property, mixed $value): bool
    {
        return false;
    }

    /**
     * Get a null type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return null
     */
    protected function __getNullValueForDataStore(string $property, mixed $value): mixed
    {
        return null;
    }

    /**
     * Get a json type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @throws JsonException
     *
     * @return string
     */
    protected function __getJsonValueForDataStore(string $property, mixed $value): string
    {
        return is_string($value)
            ? $value
            : Obj::toString($value);
    }

    /**
     * Get a array type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @throws JsonException
     *
     * @return string
     */
    protected function __getArrayValueForDataStore(string $property, mixed $value): string
    {
        return is_string($value)
            ? $value
            : Arr::toString($value);
    }

    /**
     * Get a object type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return string
     */
    protected function __getObjectValueForDataStore(string $property, mixed $value): string
    {
        return is_string($value)
            ? $value
            : serialize($value);
    }

    /**
     * Get a model type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @throws JsonException
     *
     * @return string
     */
    protected function __getModelValueForDataStore(string $property, mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return Arr::toString($value);
        }

        return $value->__toString();
    }

    /**
     * Get an enum type cast value for data store.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return string|int
     */
    protected function __getEnumValueForDataStore(string $property, mixed $value): string|int
    {
        if ($this->__isValidEnumValue($value)) {
            return $value;
        }

        /** @var BackedEnum $value */
        return $value->value;
    }
}
