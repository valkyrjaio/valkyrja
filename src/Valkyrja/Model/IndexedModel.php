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

namespace Valkyrja\Model;

/**
 * Interface IndexedModel.
 *
 * @author Melech Mizrachi
 */
interface IndexedModel extends Model
{
    /**
     * Get the index map for all properties in the model.
     *
     * <code>
     *  [
     *      'propertyName' => 0,
     *      'otherPropertyName' => 1,
     *  ]
     * </code>
     *
     * @return array
     */
    public static function getIndexes(): array;

    /**
     * Get a mapped array from a given indexed array of properties.
     *
     * @param array $properties The properties
     *
     * @return array
     */
    public static function getMappedArrayFromIndexedArray(array $properties = []): array;

    /**
     * Get an indexed array from a given mapped array of properties.
     *
     * @param array $properties The properties
     *
     * @return array
     */
    public static function getIndexedArrayFromMappedArray(array $properties = []): array;

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties The properties
     *
     * @return static
     */
    public static function fromIndexedArray(array $properties): self;

    /**
     * Set properties from an array of properties.
     *
     * @param array $properties The properties
     *
     * @return void
     */
    public function updateIndexedProperties(array $properties): void;

    /**
     * Get a new model with new properties.
     *
     * @param array $properties The properties to modify
     *
     * @return static
     */
    public function withIndexedProperties(array $properties): self;

    /**
     * Get model as an array.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function asIndexedArray(string ...$properties): array;

    /**
     * Get model as an array including only changed properties.
     *
     * @return array
     */
    public function asChangedIndexedArray(): array;

    /**
     * Get all original properties.
     *
     * @return array
     */
    public function asOriginalIndexedArray(): array;
}
