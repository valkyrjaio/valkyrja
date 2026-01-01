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

namespace Valkyrja\Type\Model\Trait;

/**
 * Trait Indexable.
 */
trait Indexable
{
    /**
     * Local cache for reversed indexes.
     *
     * <code>
     *      static::class => [
     *           0 => 'property_name',
     *           1 => 'other_property_name',
     *      ]
     * </code>
     *
     * @var array<string, array<int, string>>
     */
    protected static array $indexes = [];

    /**
     * @inheritDoc
     *
     * @return array<string, int>
     */
    public static function getIndexes(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     *
     * @return array<int, string>
     */
    public static function getReversedIndexes(): array
    {
        return self::$indexes[static::class]
            ??= array_flip(static::getIndexes());
    }

    /**
     * @inheritDoc
     *
     * @param array<int, mixed> $properties The properties
     *
     * @return array<string, mixed>
     */
    public static function getMappedArrayFromIndexedArray(array $properties = []): array
    {
        $mappedProperties = [];
        $indexes          = static::getIndexes();
        $reversedIndexes  = static::getReversedIndexes();

        /**
         * @var int   $index
         * @var mixed $value
         */
        foreach ($properties as $index => $value) {
            $name = $reversedIndexes[$index] ?? null;

            if ($name !== null) {
                /** @psalm-suppress MixedAssignment */
                $mappedProperties[$name] = $value;
            }
        }

        // Sort the array by index
        uksort($mappedProperties, static fn (string $a, string $b): int => $indexes[$a] <=> $indexes[$b]);

        return $mappedProperties;
    }

    /**
     * @inheritDoc
     *
     * @param array<string, mixed> $properties The properties
     *
     * @return array<int, mixed>
     */
    public static function getIndexedArrayFromMappedArray(array $properties = []): array
    {
        $indexedArray = [];
        $indexes      = static::getIndexes();

        /**
         * @var string $name
         * @var mixed  $value
         */
        foreach ($properties as $name => $value) {
            $index = $indexes[$name] ?? null;

            if ($index !== null) {
                /** @psalm-suppress MixedAssignment */
                $indexedArray[$index] = $value;
            }
        }

        // Sort the array by index
        ksort($indexedArray);

        return $indexedArray;
    }

    /**
     * @inheritDoc
     *
     * @param array<int, mixed> $properties The properties
     */
    public static function fromIndexedArray(array $properties): static
    {
        /** @var static $model */
        $model = static::fromArray(static::getMappedArrayFromIndexedArray($properties));

        return $model;
    }

    /**
     * @inheritDoc
     *
     * @param array<int, mixed> $properties The properties
     */
    public function updateIndexedProperties(array $properties): void
    {
        $this->updateProperties(static::getMappedArrayFromIndexedArray($properties));
    }

    /**
     * @inheritDoc
     *
     * @param array<int, mixed> $properties The properties to modify
     */
    public function withIndexedProperties(array $properties): static
    {
        /** @var static $model */
        $model = $this->withProperties(static::getMappedArrayFromIndexedArray($properties));

        return $model;
    }

    /**
     * @inheritDoc
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<int, mixed>
     */
    public function asIndexedArray(string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->asArray(...$properties));
    }

    /**
     * @inheritDoc
     *
     * @return array<int, mixed>
     */
    public function asChangedIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asChangedArray());
    }

    /**
     * @inheritDoc
     *
     * @return array<int, mixed>
     */
    public function asOriginalIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asOriginalArray());
    }
}
