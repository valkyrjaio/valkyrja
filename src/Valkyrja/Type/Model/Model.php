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

namespace Valkyrja\Type\Model;

use Closure;
use JsonException;
use Valkyrja\Type\BuiltIn\Support\Arr;
use Valkyrja\Type\BuiltIn\Support\StrCase;
use Valkyrja\Type\Model\Contract\Model as Contract;
use Valkyrja\Type\Model\Exception\RuntimeException;

use function array_filter;
use function array_walk;
use function in_array;
use function is_array;
use function is_bool;
use function is_string;
use function json_encode;
use function property_exists;

use const ARRAY_FILTER_USE_BOTH;
use const JSON_THROW_ON_ERROR;

/**
 * Class Model.
 *
 * @author Melech Mizrachi
 *
 * @phpstan-consistent-constructor
 *  Will be overridden if need be
 */
abstract class Model implements Contract
{
    /**
     * Cached list of validation logic for models.
     *
     * @var array<string, string>
     */
    protected static array $cachedValidations = [];

    /**
     * Cached list of property/method exists validation logic for models.
     *
     * @var array<string, bool>
     */
    protected static array $cachedExistsValidations = [];

    /**
     * Whether to set the original properties on creation via static::fromArray().
     *
     * @var bool
     */
    protected static bool $shouldSetOriginalProperties = true;

    /**
     * The original properties.
     *
     * @var array<string, mixed>
     */
    private array $internalOriginalProperties = [];

    /**
     * Whether the original properties have been set.
     *
     * @var bool
     */
    private bool $internalOriginalPropertiesSet = false;

    /**
     * @inheritDoc
     *
     * @see https://psalm.dev/r/309e3a322e
     *
     * @return static
     */
    public static function fromArray(array $properties): static
    {
        $model = static::internalGetNew($properties);

        $model->internalSetProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public static function fromValue(mixed $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        if (! is_array($value) && ! is_string($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        if (is_string($value)) {
            $value = Arr::fromString($value);
        }

        /** @var array<string, mixed> $value */
        return static::fromArray($value);
    }

    /**
     * Get a new static instance.
     *
     * @param array<string, mixed> $properties The properties
     *
     * @return static
     */
    protected static function internalGetNew(array $properties): static
    {
        return new static();
    }

    /**
     * Whether to set the original properties array.
     *
     * @return bool
     */
    protected static function shouldSetOriginalProperties(): bool
    {
        return static::$shouldSetOriginalProperties;
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        $methodName = $this->internalGetPropertyTypeMethodName($name, 'get');

        if ($this->internalDoesPropertyTypeMethodExist($methodName)) {
            return $this->$methodName();
        }

        return $this->{$name} ?? null;
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, mixed $value): void
    {
        $methodName = $this->internalGetPropertyTypeMethodName($name, 'set');

        $this->internalSetOriginalProperty($name, $value);

        if ($this->internalDoesPropertyTypeMethodExist($methodName)) {
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
        $methodName = $this->internalGetPropertyTypeMethodName($name, 'isset');

        if ($this->internalDoesPropertyTypeMethodExist($methodName)) {
            $isset = $this->$methodName();

            if (! is_bool($isset)) {
                throw new RuntimeException("$methodName must return a boolean");
            }

            return $isset;
        }

        return isset($this->$name);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        /** @var string $offset */
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        /** @var string $offset */
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        /** @var string $offset */
        $this->__set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    /**
     * Determine whether the model has a property.
     *
     * @param string $property The property
     *
     * @return bool
     */
    public function hasProperty(string $property): bool
    {
        return self::$cachedExistsValidations[static::class . $property] ??= property_exists($this, $property);
    }

    /**
     * @inheritDoc
     */
    public function updateProperties(array $properties): void
    {
        $this->internalSetProperties($properties);
    }

    /**
     * @inheritDoc
     */
    public function withProperties(array $properties): static
    {
        $model = clone $this;

        $model->internalSetProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function modify(callable $closure): static
    {
        $new = clone $this;

        return $closure($new);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asFlatValue(): string
    {
        return $this->__toString();
    }

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<string, mixed>
     */
    public function asArray(string ...$properties): array
    {
        // Get the public properties
        $allProperties = $this->internalGetAllProperties();

        $this->internalRemoveInternalProperties($allProperties);

        $allProperties = $this->internalCheckOnlyProperties($allProperties, $properties);

        return $this->internalSetPropertyValues($allProperties, [$this, '__get']);
    }

    /**
     * @inheritDoc
     */
    public function asChangedArray(): array
    {
        return $this->internalGetChangedProperties($this->asArray());
    }

    /**
     * @inheritDoc
     */
    public function getOriginalPropertyValue(string $name): mixed
    {
        return $this->internalOriginalProperties[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function asOriginalArray(): array
    {
        return $this->internalOriginalProperties;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $allProperties = $this->internalGetAllProperties();

        $this->internalRemoveInternalProperties($allProperties);

        return $this->internalSetPropertyValues($allProperties, [$this, 'internalGetJsonPropertyValue']);
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
     * Clone model.
     */
    public function __clone()
    {
        $this->internalSetOriginalPropertiesSetProperty();
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array<string, mixed>               $properties  The properties to set
     * @param Closure(string, mixed): mixed|null $modifyValue [optional] The closure to modify the value before setting
     *
     * @return void
     */
    protected function internalSetProperties(array $properties, Closure|null $modifyValue = null): void
    {
        array_walk(
            $properties,
            function (mixed $value, string $property) use ($modifyValue): void {
                $this->internalSetIfPropertyExists(
                    $property,
                    $modifyValue !== null
                        ? $modifyValue($property, $value)
                        : $value
                );
            }
        );

        $this->internalSetOriginalPropertiesSetProperty();
    }

    /**
     * Set a property if it exists.
     *
     * @param string $property The property
     * @param mixed  $value    The value
     *
     * @return void
     */
    protected function internalSetIfPropertyExists(string $property, mixed $value): void
    {
        if ($this->hasProperty($property)) {
            // Set the property
            $this->__set($property, $value);
        }
    }

    /**
     * Set that original properties have been set.
     *
     * @return void
     */
    protected function internalSetOriginalPropertiesSetProperty(): void
    {
        $this->internalOriginalPropertiesSet = true;
    }

    /**
     * Get a property's isset method name.
     *
     * @param string $property The property
     * @param string $type     The type (get|set|isset)
     *
     * @return string
     */
    protected function internalGetPropertyTypeMethodName(string $property, string $type): string
    {
        return self::$cachedValidations[static::class . "$type$property"]
            ??= $type . StrCase::toStudlyCase($property);
    }

    /**
     * Determine if a property type method exists.
     *
     * @param string $methodName The method name
     *
     * @return bool
     */
    protected function internalDoesPropertyTypeMethodExist(string $methodName): bool
    {
        return self::$cachedExistsValidations[static::class . "exists$methodName"]
            ??= method_exists($this, $methodName);
    }

    /**
     * Set an original property.
     *
     * @param string $name  The property name
     * @param mixed  $value The value
     *
     * @return void
     */
    protected function internalSetOriginalProperty(string $name, mixed $value): void
    {
        if (! $this->internalOriginalPropertiesSet && static::shouldSetOriginalProperties()) {
            $this->internalOriginalProperties[$name] ??= $value;
        }
    }

    /**
     * Get all properties.
     *
     * @return array<string, mixed>
     */
    protected function internalGetAllProperties(): array
    {
        /** @var array<string, mixed> */
        return get_object_vars($this);
    }

    /**
     * Remove internal model properties from an array of properties.
     *
     * @param array<string, mixed> $properties The properties
     *
     * @return void
     */
    protected function internalRemoveInternalProperties(array &$properties): void
    {
        unset($properties['internalOriginalProperties'], $properties['internalOriginalPropertiesSet']);
    }

    /**
     * Check if an array of all properties should be filtered by another list of properties.
     *
     * @param array<string, mixed> $properties     The properties
     * @param string[]             $onlyProperties A list of properties to return
     *
     * @return array<string, mixed>
     */
    protected function internalCheckOnlyProperties(array $properties, array $onlyProperties): array
    {
        if (! empty($onlyProperties)) {
            return $this->internalOnlyProperties($properties, $onlyProperties);
        }

        return $properties;
    }

    /**
     * Get an array subset of properties to return from a given list out of the returnable properties.
     *
     * @param array<string, mixed> $allProperties All the properties returnable
     * @param string[]             $properties    The properties we wish to return
     *
     * @return array<string, mixed>
     */
    protected function internalOnlyProperties(array $allProperties, array $properties): array
    {
        return array_filter(
            $allProperties,
            static fn (mixed $value, string $property) => in_array($property, $properties, true),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Get the changed properties given an array of properties.
     *
     * @param array<string, mixed> $properties The properties to check the original properties against
     *
     * @return array<string, mixed>
     */
    protected function internalGetChangedProperties(array $properties): array
    {
        return array_filter(
            $properties,
            function (mixed $value, string $property) {
                $originalProperty = $this->internalOriginalProperties[$property] ?? null;

                return $originalProperty !== $value;
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Set property values.
     *
     * @param array<string, mixed> $properties The properties
     * @param callable             $callable   The callable
     *
     * @return array<string, mixed>
     */
    protected function internalSetPropertyValues(array $properties, callable $callable): array
    {
        array_walk($properties, static fn (mixed &$value, string $property): mixed => $value = $callable($property));

        /** @var array<string, mixed> $properties */
        return $properties;
    }

    /**
     * Get a property's value for jsonSerialize.
     *
     * @param string $property The property
     *
     * @return mixed
     */
    protected function internalGetJsonPropertyValue(string $property): mixed
    {
        return $this->__get($property);
    }
}
