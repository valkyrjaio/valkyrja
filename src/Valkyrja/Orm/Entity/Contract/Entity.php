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

namespace Valkyrja\Orm\Entity\Contract;

use Valkyrja\Orm\Repository\Contract\Repository;
use Valkyrja\Type\Model\Contract\CastableModel;
use Valkyrja\Type\Model\Contract\ExposableModel;

/**
 * Interface Entity.
 *
 * @author Melech Mizrachi
 */
interface Entity extends CastableModel, ExposableModel
{
    /**
     * Get the table.
     *
     * @return non-empty-string
     */
    public static function getTableName(): string;

    /**
     * Get the id field.
     *
     * @return non-empty-string
     */
    public static function getIdField(): string;

    /**
     * Get the repository to use for this entity.
     *
     * @return class-string<Repository>|null
     */
    public static function getRepository(): string|null;

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
     * @return non-empty-string[]
     */
    public static function getRelationshipProperties(): array;

    /**
     * Get a list of fields we do not want for storage.
     *
     * @return non-empty-string[]
     */
    public static function getUnStorableFields(): array;

    /**
     * Get the id field's value.
     *
     * @return non-empty-string|int
     */
    public function getIdValue(): string|int;

    /**
     * Get the entity as an array for saving to the data store.
     *
     * @param string ...$properties [optional] An array of properties to return
     *
     * @return array<string, mixed>
     */
    public function asStorableArray(string ...$properties): array;

    /**
     * Get the entity as an array for saving to the data store including only changed properties.
     *
     * @return array<string, mixed>
     */
    public function asStorableChangedArray(): array;
}
