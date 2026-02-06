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

namespace Valkyrja\Type\Model\Abstract;

use Closure;
use JsonException;
use Override;
use Valkyrja\Type\Array\Factory\ArrayFactory;
use Valkyrja\Type\Model\Contract\ModelContract;

use function array_filter;
use function array_walk;
use function in_array;
use function property_exists;

use const ARRAY_FILTER_USE_BOTH;

/**
 * @phpstan-consistent-constructor
 *  Will be overridden if need be
 */
abstract class Model implements ModelContract
{
    /**
     * Whether to set the original properties on creation via static::fromArray().
     *
     * @var bool
     */
    protected bool $internalShouldSetOriginalProperties = true;

    /**
     * The original properties.
     *
     * @var array<string, mixed>
     */
    protected array $internalOriginalProperties = [];

    /**
     * Whether the original properties have been set.
     *
     * @var bool
     */
    protected bool $internalHaveOriginalPropertiesSet = false;

    /**
     * @inheritDoc
     *
     * @see https://psalm.dev/r/309e3a322e
     *
     * @param array<string, mixed> $properties The properties
     */
    #[Override]
    public static function fromArray(array $properties): static
    {
        $model = new static();

        $model->internalSetProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        /** @var array<string, mixed> $value */
        $value = ArrayFactory::fromMixed($value);

        return static::fromArray($value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __get(string $name): mixed
    {
        $callable = $this->internalGetCallables()[$name] ?? null;

        if ($callable !== null) {
            return $callable();
        }

        return $this->{$name} ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __set(string $name, mixed $value): void
    {
        $this->internalSetOriginalProperty($name, $value);

        $callable = $this->internalSetCallables()[$name] ?? null;

        if ($callable !== null) {
            $callable($value);

            return;
        }

        $this->{$name} = $value;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function __isset(string $name): bool
    {
        $callable = $this->internalIssetCallables()[$name] ?? null;

        if ($callable !== null) {
            return $callable();
        }

        return isset($this->$name);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetExists($offset): bool
    {
        /** @var string $offset */
        return isset($this->{$offset});
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetGet($offset): mixed
    {
        /** @var string $offset */
        return $this->__get($offset);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetSet($offset, $value): void
    {
        /** @var string $offset */
        $this->__set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    /**
     * Determine whether the model has a property.
     *
     * @param string $property The property
     */
    #[Override]
    public function hasProperty(string $property): bool
    {
        return property_exists($this, $property);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function updateProperties(array $properties): void
    {
        $this->internalSetProperties($properties);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withProperties(array $properties): static
    {
        $model = clone $this;

        $model->internalSetProperties($properties);

        return $model;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function modify(callable $closure): static
    {
        $new = clone $this;

        return $closure($new);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
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
    #[Override]
    public function asArray(string ...$properties): array
    {
        // Get the public properties
        $allProperties = $this->internalGetAllProperties();

        $this->internalRemoveInternalProperties($allProperties);

        $allProperties = $this->internalPropertiesIntersect($allProperties, $properties);

        return $this->internalSetPropertyValues($allProperties, [$this, '__get']);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asChangedArray(): array
    {
        return $this->internalGetChangedProperties($this->asArray());
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOriginalPropertyValue(string $name): mixed
    {
        return $this->internalOriginalProperties[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asOriginalArray(): array
    {
        return $this->internalOriginalProperties;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function jsonSerialize(): array
    {
        $allProperties = $this->internalGetAllProperties();

        $this->internalRemoveInternalProperties($allProperties);

        return $this->internalSetPropertyValues($allProperties, [$this, '__get']);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function __toString(): string
    {
        return ArrayFactory::toString($this->jsonSerialize());
    }

    /**
     * Clone model.
     */
    public function __clone()
    {
        $this->internalHaveOriginalPropertiesSet = true;
    }

    /**
     * Get the get custom methods.
     *
     * @return array<non-empty-string, callable():mixed>
     */
    protected function internalGetCallables(): array
    {
        return [];
    }

    /**
     * Get the set custom methods.
     *
     * @return array<non-empty-string, callable(mixed):void>
     */
    protected function internalSetCallables(): array
    {
        return [];
    }

    /**
     * Get the isset custom methods.
     *
     * @return array<non-empty-string, callable():bool>
     */
    protected function internalIssetCallables(): array
    {
        return [];
    }

    /**
     * Set properties from an array of properties.
     *
     * @param array<string, mixed>               $properties  The properties to set
     * @param Closure(string, mixed): mixed|null $modifyValue [optional] The closure to modify the value before setting
     */
    protected function internalSetProperties(array $properties, Closure|null $modifyValue = null): void
    {
        array_walk(
            $properties,
            function (mixed $value, string $property) use ($modifyValue): void {
                $this->__set(
                    $property,
                    $modifyValue !== null
                        ? $modifyValue($property, $value)
                        : $value
                );
            }
        );

        $this->internalHaveOriginalPropertiesSet = true;
    }

    /**
     * Set an original property.
     *
     * @param string $name  The property name
     * @param mixed  $value The value
     */
    protected function internalSetOriginalProperty(string $name, mixed $value): void
    {
        if (! $this->internalHaveOriginalPropertiesSet && $this->internalShouldSetOriginalProperties) {
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
     */
    protected function internalRemoveInternalProperties(array &$properties): void
    {
        unset(
            $properties['internalOriginalProperties'],
            $properties['internalHaveOriginalPropertiesSet'],
            $properties['internalShouldSetOriginalProperties']
        );
    }

    /**
     * Get an array subset of properties to return from a given list out of the returnable properties.
     *
     * @param array<string, mixed> $allProperties All the properties returnable
     * @param string[]             $properties    The properties we wish to return
     *
     * @return array<string, mixed>
     */
    protected function internalPropertiesIntersect(array $allProperties, array $properties): array
    {
        if (empty($properties)) {
            return $allProperties;
        }

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
                /** @var mixed $originalProperty */
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
        array_walk($properties, static fn (mixed &$value, string $property): mixed => /** @var mixed $value */ $value = $callable($property));

        /** @var array<string, mixed> $properties */
        return $properties;
    }
}
