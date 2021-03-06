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
