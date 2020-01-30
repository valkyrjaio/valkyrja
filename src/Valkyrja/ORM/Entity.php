<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

use Valkyrja\Model\Model;

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
    public static function getTable(): string;

    /**
     * Get the id field.
     *
     * @return string
     */
    public static function getIdField(): string;

    /**
     * Get the ORM repository.
     *
     * @return string|null
     */
    public static function getRepository(): ?string;

    /**
     * Get the properties.
     *
     * @return string[]
     */
    public function getProperties(): array;

    /**
     * Get the property types.
     * <code>
     *      [
     *          // An array to be json_encoded/decoded to/from the db
     *          'property_name' => 'array',
     *          // An object to be serialized and unserialized to/from the db
     *          'property_name' => 'object',
     *          // A related entity
     *          'property_name' => Entity::class,
     *          // An array of related entities
     *          'property_name' => [Entity::class],
     *      ]
     * </code>.
     *
     * @return array
     */
    public static function getPropertyTypes(): array;

    /**
     * A mapper of property types to properties for generating a full entity with relations.
     * NOTE: Used in conjunction with Entity::$propertyTypes. If a property type is defined
     * but a property mapper is not, then the property type is NOT automatically filled in
     * via the EntityManager and Repository. If a mapper is specified and a type is not
     * then nothing happens.
     * <code>
     *      [
     *          'property_name' => [
     *              'foreign_column_relation'  => $this->field,
     *              PropertyMap::ORDER_BY      => 'some_column',
     *              PropertyMap::LIMIT         => 1,
     *              PropertyMap::OFFSET        => 0,
     *              PropertyMap::COLUMNS       => [],
     *              PropertyMap::GET_RELATIONS => true | false,
     *          ]
     *      ]
     * </code>.
     *
     * @return array
     */
    public function getPropertyMapper(): array;

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    public function forDataStore(): array;
}
