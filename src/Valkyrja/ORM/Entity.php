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

namespace Valkyrja\ORM;

use Valkyrja\Support\Model\Model;

/**
 * Interface Entity.
 *
 * @author Melech Mizrachi
 */
interface Entity extends Model
{
    /**
     * Get the table.
     *
     * @return string
     */
    public static function getTableName(): string;

    /**
     * Get the id field.
     *
     * @return string
     */
    public static function getIdField(): string;

    /**
     * Get the repository to use for this entity.
     *
     * @return string|null
     */
    public static function getEntityRepository(): ?string;

    /**
     * Types for attributes that differ from what they were saved into the database as.
     *
     * <code>
     *      [
     *          // An object to be serialized and unserialized to/from the db
     *          'a_serialized_object' => PropertyType::OBJECT,
     *          // An array to be json_encoded/decoded to/from the db
     *          'a_stringified_array' => PropertyType::ARRAY,
     *          // Data object to be json_encoded/decoded to/from the db
     *          'data_as_json_string' => PropertyType::JSON,
     *      ]
     * </code>
     *
     * @return array
     */
    public static function getFieldCastings(): array;

    /**
     * Allowed classes for serialization of object cast properties.
     *
     * <code>
     *      [
     *          // An array of allowed classes for serialization for object types
     *          'a_serialized_object' => [ClassName::class],
     *      ]
     * </code>
     *
     * @return array
     */
    public static function getCastingAllowedClasses(): array;

    /**
     * Entity relationship properties.
     *
     * <code>
     *      [
     *          'property_name',
     *          'property_name_alt',
     *          ...
     *      ]
     * </code>
     *
     * @return array
     */
    public static function getRelationshipProperties(): array;

    /**
     * Get a list of hidden fields we can expose for storage.
     *
     * @return string[]
     */
    public static function getStorableHiddenFields(): array;

    /**
     * Set a relationship property.
     *
     * @param ORM    $orm          The ORM
     * @param string $relationship The relationship to set
     *
     * @return void
     */
    public function __setRelationship(ORM $orm, string $relationship): void;

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array
     */
    public function __storable(string ...$properties): array;
}
