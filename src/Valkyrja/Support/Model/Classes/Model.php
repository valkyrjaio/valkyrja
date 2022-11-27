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

namespace Valkyrja\Support\Model\Classes;

use BackedEnum;
use JsonException;
use UnitEnum;
use Valkyrja\Support\Model\Enums\CastType;
use Valkyrja\Support\Model\Model as Contract;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Obj;
use Valkyrja\Support\Type\Str;

use function is_array;
use function is_object;
use function is_string;
use function method_exists;
use function property_exists;

/**
 * Class Model.
 *
 * @author Melech Mizrachi
 */
abstract class Model implements Contract
{
    /**
     * Cached list of validation logic for models.
     *
     * @var array[]
     */
    protected static array $cachedValidations = [];

    /**
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // An property to be json_decoded to an array
     *          'property_name' => CastType::array,
     *          // An property to be unserialized to an object
     *          'property_name' => CastType::object,
     *          // An property to be json_decoded to an object
     *          'property_name' => CastType::json,
     *          // An property to be cast to an string
     *          'property_name' => CastType::string,
     *          // An property to be cast to an int
     *          'property_name' => CastType::int,
     *          // An property to be cast to an float
     *          'property_name' => CastType::float,
     *          // An property to be cast to an bool
     *          'property_name' => CastType::bool,
     *          // An property to be cast to an enum
     *          'property_name' => [CastType::enum, Enum::class],
     *          // An property to be cast to a model
     *          'property_name' => [CastType::model, Model::class],
     *          // An property to be cast to an array of models
     *          'property_name' => [CastType::model, [Model::class]],
     *      ]
     * </code>
     *
     * @return array<string, CastType|array<CastType, string|string[]>>
     */
    protected static array $castings = [];

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
     * @var array<string, string[]>
     */
    protected static array $castingsAllowedClasses = [];

    /**
     * Properties that are exposable.
     *
     * @var string[]
     */
    protected static array $exposable = [];

    /**
     * Whether to set the original properties on creation via static::fromArray().
     *
     * @var bool
     */
    protected static bool $setOriginalPropertiesFromArray = true;

    /**
     * The properties to expose.
     *
     * @var string[]
     */
    protected array $__exposed = [];

    /**
     * The original properties.
     *
     * @var array
     */
    protected array $__originalProperties = [];

    /**
     * @inheritDoc
     */
    public static function getExposable(): array
    {
        return static::$exposable;
    }

    /**
     * @inheritDoc
     */
    public static function getCastings(): array
    {
        return static::$castings;
    }

    /**
     * @inheritDoc
     */
    public static function getCastingsAllowedClasses(): array
    {
        return static::$castingsAllowedClasses;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public static function fromArray(array $properties): self
    {
        $model = static::__getNew($properties);

        $model->__setProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        $methodName = self::$cachedValidations[static::class . "get$name"] ??= 'get' . Str::toStudlyCase($name);

        if (self::$cachedValidations[static::class . "exists$methodName"] ??= method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return $this->{$name} ?? null;
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, mixed $value): void
    {
        $methodName = self::$cachedValidations[static::class . "set$name"] ??= 'set' . Str::toStudlyCase($name);

        if (self::$cachedValidations[static::class . "exists$methodName"] ??= method_exists($this, $methodName)) {
            $this->$methodName($value);

            return;
        }

        if (static::$setOriginalPropertiesFromArray) {
            $this->__originalProperties[$name] ??= $value;
        }

        $this->{$name} = $value;
    }

    /**
     * @inheritDoc
     */
    public function __isset(string $name): bool
    {
        $methodName = self::$cachedValidations[static::class . "isset$name"] ??= 'isset' . Str::toStudlyCase($name);

        if (self::$cachedValidations[static::class . "exists$methodName"] ??= method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return isset($this->$name);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function updateProperties(array $properties): void
    {
        $this->__setProperties($properties);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function withProperties(array $properties): self
    {
        $model = clone $this;

        $model->__setProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function asArray(string ...$properties): array
    {
        return $this->__asArray(false, false, ...$properties);
    }

    /**
     * @inheritDoc
     */
    public function asExposedArray(string ...$properties): array
    {
        return $this->__asExposedArray(false, ...$properties);
    }

    /**
     * @inheritDoc
     */
    public function asChangedArray(): array
    {
        return $this->__asChangedArray();
    }

    public function asExposedChangedArray(): array
    {
        return $this->__asExposedChangedArray();
    }

    /**
     * @inheritDoc
     */
    public function getOriginalPropertyValue(string $name): mixed
    {
        return $this->__originalProperties[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function asOriginalArray(): array
    {
        return $this->__originalProperties;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->__asArray(true);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function __toString(): string
    {
        return Arr::toString($this->jsonSerialize());
    }

    /**
     * @inheritDoc
     */
    public function expose(string ...$properties): void
    {
        foreach ($properties as $property) {
            $this->__exposed[$property] = true;
        }
    }

    /**
     * @inheritDoc
     */
    public function unexpose(string ...$properties): void
    {
        if (empty($properties)) {
            $this->__exposed = [];

            return;
        }

        foreach ($properties as $property) {
            unset($this->__exposed[$property]);
        }
    }

    /**
     * Get a new instance.
     *
     * @param array $properties The properties
     *
     * @return $this
     */
    protected static function __getNew(array $properties): self
    {
        return new static();
    }

    /**
     * Get an array subset of properties to return from a given list out of the returnable properties.
     *
     * @param array $allProperties All the properties returnable
     * @param array $properties    The properties we wish to return
     *
     * @return array
     */
    protected function __onlyProperties(array $allProperties, array $properties): array
    {
        $onlyProperties = [];

        // Iterate through the list and set only those properties if the property exists in the allProperties array
        // NOTE: The allProperties array will already have gone through logic to get exposable properties, so only
        //       if the property exists in this array should we return it in the onlyProperties array.
        foreach ($properties as $onlyProperty) {
            if (isset($allProperties[$onlyProperty])) {
                $onlyProperties[$onlyProperty] = true;
            }
        }

        // Return the properties requested
        return $onlyProperties;
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties The properties to set
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function __setProperties(array $properties): void
    {
        $castings    = static::getCastings();
        $hasCastings = self::$cachedValidations[static::class . 'hasCastings'] ??= ! empty($castings);

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            if (self::$cachedValidations[static::class . $property] ??= property_exists($this, $property)) {
                // Set the property
                $this->__set(
                    $property,
                    $hasCastings
                        ? $this->__getPropertyValueByType($castings, $property, $value)
                        : $value
                );
            }
        }
    }

    /**
     * Get a property's value by the type (if type is set).
     *
     * @param array  $castings The castings
     * @param string $property The property name
     * @param mixed  $value    The property value
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getPropertyValueByType(array $castings, string $property, mixed $value): mixed
    {
        // If there is no type specified or the value is null just return the value
        // Castings assignment is set in the if specifically to avoid an assignment
        // if the value is null, which would be an unneeded assigned variable
        if ($value === null || ($type = $castings[$property] ?? null) === null) {
            return $value;
        }

        return match ($this->__getTypeToCheck($type)) {
            CastType::string => (string) $value,
            CastType::int    => (int) $value,
            CastType::float  => (float) $value,
            CastType::double => (double) $value,
            CastType::bool   => (bool) $value,
            CastType::model  => $this->__getModelFromValueType($property, $type[1], $value),
            CastType::enum   => $this->__getEnumFromValueType($property, $type[1], $value),
            CastType::array  => is_string($value) ? Arr::fromString($value) : (array) $value,
            CastType::json   => is_string($value) ? Obj::fromString($value) : (object) $value,
            CastType::object => is_string($value)
                ? unserialize(
                    $value,
                    [
                        'allowed_classes' => static::getCastingsAllowedClasses()[$property] ?? [],
                    ]
                )
                : (object) $value,
            CastType::true   => true,
            CastType::false  => false,
            CastType::null   => null,
        };
    }

    /**
     * Get the type to check. Could be an array for models or enums since the second index will be the enum/model name.
     *
     * @param CastType|array $type The type
     *
     * @return CastType
     */
    protected function __getTypeToCheck(CastType|array $type): CastType
    {
        return $type instanceof CastType
            ? $type
            : $type[0];
    }

    /**
     * Get a model from value given a type not identified prior.
     *
     * @param string       $property The property name
     * @param array|string $type     The type of the property
     * @param mixed        $value    The value
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getModelFromValueType(string $property, array|string $type, mixed $value): mixed
    {
        // An array would indicate an array of models
        if (is_array($type)) {
            $type = $type[0];

            return array_map(
                function (array $data) use ($property, $type) {
                    return $this->__getModelFromValue($property, $type, $data);
                },
                $value
            );
        }

        return $this->__getModelFromValue($property, $type, $value);
    }

    /**
     * Get a model from value.
     *
     * @param string $property The property name
     * @param string $type     The type of the property
     * @param mixed  $value    The value
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getModelFromValue(string $property, string $type, mixed $value): mixed
    {
        if (is_string($value)) {
            $value = Arr::fromString($value);
        } elseif ($value instanceof Contract) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value) || is_array($value)) {
            $value = (array) $value;
        } else {
            // Return the value as is since it does not seem to match what we're expecting if we were to get a model
            // from the value data
            // Worth wondering how we got here in the first place... This really shouldn't be possible
            return $value;
        }

        if (isset($this->$property) && $this->$property instanceof Contract) {
            $value = array_merge($this->$property->asArray(), $value);
        }

        /** @var static $type */
        return $type::fromArray($value);
    }

    /**
     * Get an enum from value given a type not identified prior.
     *
     * @param string       $property The property name
     * @param array|string $type     The type of the property
     * @param mixed        $value    The value
     *
     * @return mixed
     */
    protected function __getEnumFromValueType(string $property, array|string $type, mixed $value): mixed
    {
        // An array would indicate an array of enums
        if (is_array($type)) {
            $type = $type[0];

            return array_map(
                function (array $data) use ($property, $type) {
                    return $this->__getEnumFromValue($property, $type, $data);
                },
                $value
            );
        }

        return $this->__getEnumFromValue($property, $type, $value);
    }

    /**
     * Get an enum from value.
     *
     * @param string $property The property name
     * @param string $type     The type of the property
     * @param mixed  $value    The value
     *
     * @return mixed
     */
    protected function __getEnumFromValue(string $property, string $type, mixed $value): mixed
    {
        // If it's already an enum just send it along the way
        if ($value instanceof UnitEnum) {
            return $value;
        }

        /** @var BackedEnum $type */
        return $type::tryFrom($value);
    }

    /**
     * Convert the entity to an array or json array.
     *
     * @param bool   $toJson        [optional] Whether to get as a json array
     * @param bool   $includeHidden [optional] Whether to include hidden properties
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asArray(bool $toJson = false, bool $includeHidden = false, string ...$properties): array
    {
        // Get the public properties
        $allProperties = $this->__allProperties($includeHidden);
        $propertyTypes = static::getCastings();

        unset($allProperties['__exposed'], $allProperties['__originalProperties']);

        if (! empty($properties)) {
            $allProperties = $this->__onlyProperties($allProperties, $properties);
        }

        // Ensure for each property we use the magic __get method so as to go through any magic get{Property} methods
        foreach ($allProperties as $property => $value) {
            $allProperties[$property] = $this->__getAsArrayPropertyValue($propertyTypes, $property, $toJson);

            // Remove properties with null value if model flag is set to do so.
        }

        return $allProperties;
    }

    /**
     * Get all properties.
     *
     * @param bool $includeHidden [optional] Whether to include hidden properties
     *
     * @return array
     */
    protected function __allProperties(bool $includeHidden = false): array
    {
        return $includeHidden
            ? get_object_vars($this)
            : array_merge(Obj::getProperties($this), $this->__exposed);
    }

    /**
     * @param bool   $toJson        [optional] Whether to get as a json array.
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asExposedArray(bool $toJson = false, string ...$properties): array
    {
        $this->expose(...static::$exposable);
        $array = $this->__asArray($toJson, false, ...$properties);
        $this->unexpose(...static::$exposable);

        return $array;
    }

    /**
     * @param bool $toJson [optional] Whether to get as a json array.
     *
     * @return array
     */
    protected function __asChangedArray(bool $toJson = false): array
    {
        // The original properties set on the model
        $originalProperties = $this->__originalProperties;
        // The changed properties
        $changed = [];

        // Iterate through the model's properties
        foreach ($this->__asArrayForChangedComparison($toJson) as $property => $value) {
            $originalProperty = $originalProperties[$property] ?? null;

            // Determine if the property changed
            if ($originalProperty !== $value) {
                $changed[$property] = $value;
            }
        }

        return $changed;
    }

    /**
     * The model as an array to compare with original properties to determine what changed.
     *
     * @param bool $toJson [optional] Whether to get as a json array.
     *
     * @return array
     */
    protected function __asArrayForChangedComparison(bool $toJson = false): array
    {
        return $this->__asArray($toJson);
    }

    /**
     * The model as an array to compare with original properties to determine what changed along with exposable
     * properties.
     *
     * @param bool $toJson [optional] Whether to get as a json array.
     *
     * @return array
     */
    protected function __asExposedChangedArray(bool $toJson = false): array
    {
        $this->expose(...static::$exposable);
        $array = $this->__asChangedArray($toJson);
        $this->unexpose(...static::$exposable);

        return $array;
    }

    /**
     * Get a property's value for to array.
     *
     * @param array  $castings The castings
     * @param string $property The property
     * @param bool   $toJson   [optional] Whether to get as a json array.
     *
     * @return mixed
     */
    protected function __getAsArrayPropertyValue(array $castings, string $property, bool $toJson): mixed
    {
        $value = $this->__get($property);

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }
}
