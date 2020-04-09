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
    public static function getEntityTable(): string;

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
     * Get the property types map.
     *
     * @return array
     */
    public static function getPropertyTypes(): array;

    /**
     * Get the id field value.
     *
     * @return string
     */
    public function getIdFieldValue(): string;

    /**
     * Set the id field value.
     *
     * @param string $id
     *
     * @return void
     */
    public function setIdFieldValue(string $id): void;

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @return array
     */
    public function forDataStore(): array;

    /**
     * Get all the relations for the entity as defined in getPropertyTypes and getPropertyMapper.
     *
     * @param array|null $columns
     *
     * @return void
     */
    public function setEntityRelations(array $columns = null): void;
}
