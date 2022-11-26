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

namespace Valkyrja\Support\Model\Traits;

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
        return $this->__asIndexedArray(false, false, ...$properties);
    }

    /**
     * @inheritDoc
     */
    public function asIndexedArrayWithExposable(string ...$properties): array
    {
        return $this->__asIndexedArrayWithExposable(false, ...$properties);
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

    /**
     * Get model as an array.
     *
     * @param bool   $toJson        [optional] Whether to get as a json array
     * @param bool   $all           [optional] Whether to get all properties
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asIndexedArray(bool $toJson = false, bool $all = false, string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->__asArray($toJson, $all, ...$properties));
    }

    /**
     * Get model as an array with exposable properties.
     *
     * @param bool   $toJson        [optional] Whether to get as a json array.
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    protected function __asIndexedArrayWithExposable(bool $toJson = false, string ...$properties): array
    {
        return static::getIndexedArrayFromMappedArray($this->__asArrayWithExposable($toJson, ...$properties));
    }
}
