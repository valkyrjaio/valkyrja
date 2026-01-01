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

namespace Valkyrja\Type\Model\Contract;

/**
 * Interface IndexedModelContract.
 */
interface IndexedModelContract extends ModelContract
{
    /**
     * Get the index map for all properties in the model.
     *
     * <code>
     *  [
     *      'property_name' => 0,
     *      'other_property_name' => 1,
     *  ]
     * </code>
     *
     * @return array<string, int>
     */
    public static function getIndexes(): array;

    /**
     * Get the reversed index map for all properties in the model.
     *
     * <code>
     *  [
     *      'property_name' => 0,
     *      'other_property_name' => 1,
     *  ]
     * </code>
     *
     * @return array<int, string>
     */
    public static function getReversedIndexes(): array;

    /**
     * Get a mapped array from a given indexed array of properties.
     *
     * @param array<int, mixed> $properties The properties
     *
     * @return array<string, mixed>
     */
    public static function getMappedArrayFromIndexedArray(array $properties = []): array;

    /**
     * Get an indexed array from a given mapped array of properties.
     *
     * @param array<string, mixed> $properties The properties
     *
     * @return array<int, mixed>
     */
    public static function getIndexedArrayFromMappedArray(array $properties = []): array;

    /**
     * Set properties from an array of properties.
     *
     * @param array<int, mixed> $properties The properties
     *
     * @return static
     */
    public static function fromIndexedArray(array $properties): static;

    /**
     * Set properties from an array of properties.
     *
     * @param array<int, mixed> $properties The properties
     *
     * @return void
     */
    public function updateIndexedProperties(array $properties): void;

    /**
     * Get a new model with new properties.
     *
     * @param array<int, mixed> $properties The properties to modify
     *
     * @return static
     */
    public function withIndexedProperties(array $properties): static;

    /**
     * Get model as an array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<int, mixed>
     */
    public function asIndexedArray(string ...$properties): array;

    /**
     * Get model as an array including only changed properties.
     *
     * @return array<int, mixed>
     */
    public function asChangedIndexedArray(): array;

    /**
     * Get all original properties.
     *
     * @return array<int, mixed>
     */
    public function asOriginalIndexedArray(): array;
}
