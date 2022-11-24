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
use Valkyrja\Support\Model\Constants\PropertyType;
use Valkyrja\Support\Model\Enums\CastType;
use Valkyrja\Support\Model\Model as Contract;
use Valkyrja\Support\Type\Arr;
use Valkyrja\Support\Type\Cls;
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
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // An property to be json_decoded to an array
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::array,
     *          // An property to be unserialized to an object
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::object,
     *          // An property to be json_decoded to an object
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::json,
     *          // An property to be cast to an string
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::string,
     *          // An property to be cast to an int
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::int,
     *          // An property to be cast to an float
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::float,
     *          // An property to be cast to an bool
     *          'property_name' => \Valkyrja\Support\Model\Enums\CastType::bool,
     *          // An property to be cast to an enum
     *          'property_name' => Enum::class,
     *          // An property to be cast to a model
     *          'property_name' => Model::class,
     *          // An property to be cast to an array of models
     *          'property_name' => [Model::class],
     *      ]
     * </code>
     *
     * @var array<string, string|string[]>
     */
    protected static array $propertyCastings = [];

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
        return static::$propertyCastings;
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
    public static function fromArray(array $properties): static
    {
        $model = new static();

        $model->__setProperties($properties, true);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        $methodName = 'get' . Str::toStudlyCase($name);

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        return $this->{$name} ?? null;
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, mixed $value): void
    {
        $methodName = 'set' . Str::toStudlyCase($name);

        if (method_exists($this, $methodName)) {
            $this->$methodName($value);

            return;
        }

        $this->{$name} = $value;
    }

    /**
     * @inheritDoc
     */
    public function __isset(string $name): bool
    {
        $methodName = 'isset' . Str::toStudlyCase($name);

        if (method_exists($this, $methodName)) {
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
    public function withProperties(array $properties): static
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
        return $this->__asArray(false, ...$properties);
    }

    /**
     * @inheritDoc
     */
    public function asArrayWithExposable(string ...$properties): array
    {
        return $this->__asArrayWithExposable(false, ...$properties);
    }

    /**
     * @inheritDoc
     */
    public function asChangedArray(): array
    {
        return $this->__asChangedArray();
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
     * @param array $properties            The properties to set
     * @param bool  $setOriginalProperties [optional] Whether to set the original properties
     *
     * @throws JsonException
     *
     * @return void
     */
    protected function __setProperties(array $properties, bool $setOriginalProperties = false): void
    {
        $propertyTypes          = static::getCastings();
        $propertyAllowedClasses = static::getCastingsAllowedClasses();

        // Iterate through the properties
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                if ($setOriginalProperties && ! isset($this->__originalProperties[$property])) {
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
        mixed $value
    ): mixed {
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        return match ($type) {
            CastType::object, PropertyType::OBJECT => is_string($value)
                ? unserialize(
                    $value,
                    [
                        'allowed_classes' => $propertyAllowedClasses[$property] ?? [],
                    ]
                )
                : $value,
            CastType::array, PropertyType::ARRAY   => is_string($value) ? Arr::fromString($value) : $value,
            CastType::json, PropertyType::JSON     => is_string($value) ? Obj::fromString($value) : $value,
            CastType::string, PropertyType::STRING => (string) $value,
            CastType::int, PropertyType::INT       => (int) $value,
            CastType::float, PropertyType::FLOAT   => (float) $value,
            CastType::bool, PropertyType::BOOL     => (bool) $value,
            default                                => $this->__getModelFromValueType($property, $type, $value),
        };
    }

    /**
     * Get a model from value given a type not identified prior.
     *
     * @param string       $property The property name
     * @param string|array $type     The type of the property
     * @param mixed        $value    The value
     *
     * @throws JsonException
     *
     * @return mixed
     */
    protected function __getModelFromValueType(string $property, string|array $type, mixed $value): mixed
    {
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
        if ($value instanceof Contract) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value) || is_array($value)) {
            $value = (array) $value;
        } elseif (is_string($value) && Cls::inherits($type, Contract::class)) {
            $value = Arr::fromString($value);
        } else {
            if (! ($value instanceof BackedEnum) && Cls::inherits($type, BackedEnum::class)) {
                /** @var BackedEnum $type */
                return $type::tryFrom($value);
            }

            if (! ($value instanceof UnitEnum) && Cls::inherits($type, UnitEnum::class)) {
                return unserialize(
                    $value,
                    [
                        'allowed_classes' => $type,
                    ]
                );
            }

            // Return the value as is since it does not seem to match what we're expecting if we were to get a model
            // from the value data
            return $value;
        }

        if (isset($this->$property) && $this->$property instanceof Contract) {
            $value = array_merge($this->$property->asArray(), $value);
        }

        /** @var static $type */
        return $type::fromArray((array) $value);
    }

    /**
     * Convert the entity to an array or json array.
     *
     * @param bool   $toJson        [optional] Whether to get as a json array.
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asArray(bool $toJson = false, string ...$properties): array
    {
        // Get the public properties
        $allProperties = array_merge(Obj::getProperties($this), $this->__exposed);
        $propertyTypes = static::getCastings();

        if (! empty($properties)) {
            $allProperties = $this->__onlyProperties($allProperties, $properties);
        }

        unset($allProperties['__exposed'], $allProperties['__originalProperties']);

        // Ensure for each property we use the magic __get method so as to go through any magic get{Property} methods
        foreach ($allProperties as $property => $value) {
            $allProperties[$property] = $this->__getAsArrayPropertyValue($propertyTypes, $property, $toJson);

            // Remove properties with null value if model flag is set to do so.
        }

        return $allProperties;
    }

    /**
     * @param bool   $toJson        [optional] Whether to get as a json array.
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asArrayWithExposable(bool $toJson = false, string ...$properties): array
    {
        $this->expose(...static::$exposable);
        $array = $this->__asArray($toJson, ...$properties);
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
     * Get a property's value for to array.
     *
     * @param array  $propertyTypes The property types
     * @param string $property      The property
     * @param bool   $toJson        [optional] Whether to get as a json array.
     *
     * @return mixed
     */
    protected function __getAsArrayPropertyValue(array $propertyTypes, string $property, bool $toJson): mixed
    {
        $value = $this->__get($property);

        // If this is a json array we're building
        if ($toJson) {
            if ($value instanceof BackedEnum) {
                return $value->value;
            }

            if ($value instanceof UnitEnum) {
                return serialize($value);
            }
        }

        return $value;
    }
}
