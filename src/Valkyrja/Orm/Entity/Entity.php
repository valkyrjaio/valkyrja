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

namespace Valkyrja\Orm\Entity;

use JsonException;
use Override;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\Exception\RuntimeException;
use Valkyrja\Orm\Entity\Contract\Entity as Contract;
use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\Contract\Type;
use Valkyrja\Type\Data\Cast;
use Valkyrja\Type\Model\Castable;
use Valkyrja\Type\Model\Model;
use Valkyrja\Type\Model\ProtectedExposable;

use function array_walk;
use function gettype;
use function is_array;
use function is_int;
use function is_string;

/**
 * Class Entity.
 *
 * @author Melech Mizrachi
 *
 * @psalm-import-type StorableValue from Contract
 *
 * @phpstan-import-type StorableValue from Contract
 */
abstract class Entity extends Model implements Contract
{
    use Castable;
    use ProtectedExposable;

    /**
     * The table name.
     *
     * @var non-empty-string
     */
    protected static string $tableName;

    /**
     * The id field.
     *
     * @var non-empty-string
     */
    protected static string $idField = 'id';

    /**
     * The repository.
     *
     * @var class-string<Repository>|null
     */
    protected static string|null $repository = null;

    /**
     * A list of hidden fields we can expose for storage.
     *
     * @var non-empty-string[]
     */
    protected static array $relationshipProperties = [];

    /**
     * A list of fields we do not want to store.
     *
     * @var non-empty-string[]
     */
    protected static array $unStorableFields = [];

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getTableName(): string
    {
        return static::$tableName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getIdField(): string
    {
        return static::$idField;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getRepository(): string|null
    {
        return static::$repository;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getRelationshipProperties(): array
    {
        return static::$relationshipProperties;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getUnStorableFields(): array
    {
        return static::$unStorableFields;
    }

    /**
     * Get the id field's value.
     */
    #[Override]
    public function getIdValue(): string|int
    {
        /** @var mixed $id */
        $id = $this->__get(static::getIdField());

        if (is_int($id) || (is_string($id) && $id !== '')) {
            /** @var non-empty-string|int $id */
            return $id;
        }

        throw new RuntimeException('Id field value should be a string or int');
    }

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @throws JsonException
     *
     * @return array<non-empty-string, StorableValue>
     */
    #[Override]
    public function asStorableArray(string ...$properties): array
    {
        $unStorableFields = array_merge(static::getUnStorableFields(), static::getRelationshipProperties());
        // Get the public properties
        $allProperties = $this->internalAllPropertiesForStorage();
        $castings      = $this->internalGetCastings();

        $this->internalRemoveInternalProperties($allProperties);

        /** @var array<string, mixed> $allProperties */
        $allProperties = $this->internalCheckOnlyProperties($allProperties, $properties);

        $this->internalRemoveUnStorableFields($allProperties, $unStorableFields);

        /** @var array<non-empty-string, StorableValue> $properties */
        $properties = $this->internalGetPropertyValuesForDataStore($allProperties, $castings);

        return $properties;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function asStorableChangedArray(): array
    {
        /** @var array<non-empty-string, StorableValue> $properties */
        $properties = $this->internalGetChangedProperties($this->asStorableArray());

        return $properties;
    }

    /**
     * Get all properties for storage.
     *
     * @return array<string, mixed>
     */
    protected function internalAllPropertiesForStorage(): array
    {
        /** @var array<string, mixed> */
        return get_object_vars($this);
    }

    /**
     * Remove un-storable entity properties from an array of properties.
     *
     * @param array<string, mixed> $allProperties    The properties
     * @param string[]             $unStorableFields The un-storable fields
     *
     * @return void
     */
    protected function internalRemoveUnStorableFields(array &$allProperties, array $unStorableFields): void
    {
        array_walk($unStorableFields, static function (string $unStorableHiddenField) use (&$allProperties): void {
            // Remove the un-storable field to the all properties array
            unset($allProperties[$unStorableHiddenField]);
        });
    }

    /**
     * Get property values for data store.
     *
     * @param array<string, mixed> $allProperties The properties
     * @param array<string, Cast>  $castings      The castings
     *
     * @throws JsonException
     *
     * @return array<non-empty-string, StorableValue>
     */
    protected function internalGetPropertyValuesForDataStore(array $allProperties, array $castings): array
    {
        array_walk(
            $allProperties,
            fn (mixed &$value, string $property): mixed => /** @var mixed $value */ $value
                = $this->internalGetPropertyValueForDataStore($castings, $property)
        );

        /** @var array<non-empty-string, StorableValue> $allProperties */
        return $allProperties;
    }

    /**
     * Get a property's value for data store.
     *
     * @param array<string, Cast> $castings The castings
     * @param string              $property The property name
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function internalGetPropertyValueForDataStore(array $castings, string $property): mixed
    {
        $value = $this->__get($property);

        // If there is no type specified or the value is null just return the value
        // Castings assignment is set in the if specifically to avoid an assignment
        // if the value is null, which would be an unneeded assigned variable
        if ($value === null || ($cast = $castings[$property] ?? null) === null) {
            return $value;
        }

        // An array would indicate an array of types
        if ($cast->isArray) {
            if (! is_array($value)) {
                $typeOf = gettype($value);

                throw new InvalidArgumentException("Expecting array, $typeOf provided, for $property cast");
            }

            return Arr::toString(
                array_map(
                    fn (mixed $data): mixed => $this->internalGetTypeValueForDataStore($cast, $data),
                    $value
                )
            );
        }

        return $this->internalGetTypeValueForDataStore($cast, $value);
    }

    /**
     * Get a Type type cast value for data store.
     *
     * @param Cast  $cast  The cast for the property
     * @param mixed $value The value
     *
     * @return mixed
     */
    protected function internalGetTypeValueForDataStore(Cast $cast, mixed $value): mixed
    {
        /** @var class-string<Type> $type */
        $type = $cast->type;

        if ($value instanceof Type) {
            return $value->asFlatValue();
        }

        $typeInstance = $type::fromValue($value);

        return $typeInstance->asFlatValue();
    }
}
