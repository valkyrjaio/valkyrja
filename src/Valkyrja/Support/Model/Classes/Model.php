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

use JsonException;
use Valkyrja\Support\Model\Constants\PropertyType;
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
     * Property castings used for mass property sets to avoid needing individual setters for simple type casting.
     *
     * <code>
     *      [
     *          // An property to be json_decoded to an array
     *          'property_name' => 'array',
     *          // An property to be unserialized to an object
     *          'property_name' => 'object',
     *          // An property to be json_decoded to an object
     *          'property_name' => 'json',
     *          // An property to be cast to an string
     *          'property_name' => 'string',
     *          // An property to be cast to an int
     *          'property_name' => 'int',
     *          // An property to be cast to an float
     *          'property_name' => 'float',
     *          // An property to be cast to an bool
     *          'property_name' => 'bool',
     *          // An property to be cast to a model
     *          'property_name' => Model::class,
     *          // An property to be cast to an array of models
     *          'property_name' => [Model::class],
     *      ]
     * </code>
     *
     * @var array
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
     * @var array
     */
    protected static array $castingsAllowedClasses = [];

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
     *
     * @throws JsonException
     */
    public static function fromArray(array $properties): self
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

        return $this->{$name};
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value): void
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
        // Get the public properties
        $allProperties = array_merge(Obj::getProperties($this), $this->__exposed);

        if (! empty($properties)) {
            $allProperties = $this->__onlyProperties($allProperties, $properties);
        }

        // Ensure for each property we use the magic __get method so as to go through any magic get{Property} methods
        foreach ($allProperties as $property => $value) {
            $allProperties[$property] = $this->__get($property);
        }

        unset($allProperties['__exposed'], $allProperties['__originalProperties']);

        return $allProperties;
    }

    /**
     * @inheritDoc
     */
    public function asChangedArray(): array
    {
        // The original properties set on the model
        $originalProperties = $this->__originalProperties;
        // The changed properties
        $changed = [];

        // Iterate through the model's properties
        foreach ($this->__asArrayForChangedComparison() as $property => $value) {
            $originalProperty = $originalProperties[$property] ?? null;

            // Determine if the property changed
            if ($originalProperty !== $value) {
                $changed[$property] = $value;
            }
        }

        return $changed;
    }

    /**
     * @inheritDoc
     */
    public function getOriginalPropertyValue(string $name)
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
        return $this->asArray();
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
     * The model as an array to compare with original properties to determine what changed.
     *
     * @return array
     */
    protected function __asArrayForChangedComparison(): array
    {
        return $this->asArray();
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

        // Iterate through the list and set only those properties
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
        $propertyTypes          = static::$propertyCastings;
        $propertyAllowedClasses = static::$castingsAllowedClasses;

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
        $value
    ) {
        // Check if a type was set for this attribute
        $type = $propertyTypes[$property] ?? null;

        // If there is no type specified just return the value
        if (null === $type) {
            return $value;
        }

        switch ($type) {
            case PropertyType::OBJECT :
                if (is_string($value)) {
                    $value = unserialize(
                        $value,
                        [
                            'allowed_classes' => $propertyAllowedClasses[$property] ?? [],
                        ]
                    );
                }

                break;
            case PropertyType::ARRAY :
                if (is_string($value)) {
                    $value = Arr::fromString($value);
                }

                break;
            case PropertyType::JSON :
                if (is_string($value)) {
                    $value = Obj::fromString($value);
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
                $value = $this->__getModelFromValueType($property, $type, $value);

                break;
        }

        return $value;
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
    protected function __getModelFromValueType(string $property, $type, $value)
    {
        if (is_array($type)) {
            $type = $type[0];

            foreach ($value as &$item) {
                $item = $this->__getModelFromValue($property, $type, $item);
            }

            unset($item);

            return $value;
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
    protected function __getModelFromValue(string $property, string $type, $value)
    {
        if ($value instanceof Contract) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value) || is_array($value)) {
            $value = (array) $value;
        } elseif (is_string($value)) {
            $value = Arr::fromString($value);
        } else {
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
}
