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
use Valkyrja\ORM\Entity as Contract;
use Valkyrja\Support\Model\Classes\Model;
use Valkyrja\Support\Model\Enums\CastType;
use Valkyrja\Support\Model\Model as ModelContract;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;

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
    protected function __asChangedArray(bool $toJson = true): array
    {
        return parent::__asChangedArray($toJson);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    protected function __asArrayForChangedComparison(bool $toJson = true): array
    {
        return $this->__asStorableArray($toJson);
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
        $value = $this->__getAsArrayPropertyValue($propertyTypes, $property, true);
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        return match ($type) {
            CastType::object => ! is_string($value) ? serialize($value) : $value,
            CastType::array  => ! is_string($value) ? Arr::toString($value) : $value,
            CastType::json   => ! is_string($value) ? Obj::toString($value) : $value,
            CastType::string => (string) $value,
            CastType::int    => (int) $value,
            CastType::float  => (float) $value,
            CastType::bool   => (bool) $value,
            default          => ($value instanceof ModelContract) ? $value->__toString() : $value,
        };
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
        $propertyTypes    = static::getCastings();

        // Iterate through all the un-storable fields
        foreach ($unStorableFields as $unStorableHiddenField) {
            // Remove the un-storable field to the all properties array
            unset($allProperties[$unStorableHiddenField]);
        }

        // Iterate through the properties to return
        foreach ($allProperties as $property => $value) {
            // Get the value
            $allProperties[$property] = $this->__getPropertyValueForDataStore($propertyTypes, $property);
        }

        return $allProperties;
    }
}
