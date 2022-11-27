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

namespace Valkyrja\Model\Traits;

use JsonException;

/**
 * Trait IndexedModelTrait.
 *
 * @author Melech Mizrachi
 */
trait IndexedModelTrait
{
    /**
     * @inheritDoc
     */
    public static function getIndexes(): array
    {
        return [];
    }

    /**
     * @inheritDoc
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
    public static function fromIndexedArray(array $properties): self
    {
        return static::fromArray(static::getMappedArrayFromIndexedArray($properties));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function updateIndexedProperties(array $properties): void
    {
        $this->updateProperties(static::getMappedArrayFromIndexedArray($properties));
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function withIndexedProperties(array $properties): self
    {
        return $this->withProperties(static::getMappedArrayFromIndexedArray($properties));
    }

    /**
     * @inheritDoc
     */
    public function asIndexedArray(string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->asArray(...$properties));
    }

    /**
     * @inheritDoc
     */
    public function asIndexedArrayWithExposable(string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->asExposedArray(...$properties));
    }

    /**
     * @inheritDoc
     */
    public function asChangedIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asChangedArray());
    }

    /**
     * @inheritDoc
     */
    public function asOriginalIndexedArray(): array
    {
        return static::getIndexedArrayFromMappedArray($this->asOriginalArray());
    }
}
