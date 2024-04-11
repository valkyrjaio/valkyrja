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

namespace Valkyrja\Model\Models;

/**
 * Trait Indexable.
 *
 * @author Melech Mizrachi
 */
trait Indexable
{
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
     * @return array<string, mixed>
     */
    public static function getMappedArrayFromIndexedArray(array $properties = []): array
    {
        $mappedProperties = [];

        foreach (static::getIndexes() as $name => $index) {
            if (isset($properties[$index])) {
                $mappedProperties[$name] = $properties[$index];
            }
        }

        return $mappedProperties;
    }

    /**
     * @inheritDoc
     *
     * @return array<int, mixed>
     */
    public static function getIndexedArrayFromMappedArray(array $properties = []): array
    {
        $indexedArray = [];

        foreach (static::getIndexes() as $name => $index) {
            if (isset($properties[$name])) {
                $indexedArray[$index] = $properties[$name];
            }
        }

        return $indexedArray;
    }

    /**
     * @inheritDoc
     */
    public static function fromIndexedArray(array $properties): static
    {
        /** @var static $model */
        $model = static::fromArray(static::getMappedArrayFromIndexedArray($properties));

        return $model;
    }

    /**
     * @inheritDoc
     */
    public function updateIndexedProperties(array $properties): void
    {
        $this->updateProperties(static::getMappedArrayFromIndexedArray($properties));
    }

    /**
     * @inheritDoc
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
