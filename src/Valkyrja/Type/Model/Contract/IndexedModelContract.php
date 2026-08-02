<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Model\Contract;

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
     */
    public static function fromIndexedArray(array $properties): static;

    /**
     * Set properties from an array of properties.
     *
     * @param array<int, mixed> $properties The properties
     */
    public function updateIndexedProperties(array $properties): void;

    /**
     * Get a new model with new properties.
     *
     * @param array<int, mixed> $properties The properties to modify
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
