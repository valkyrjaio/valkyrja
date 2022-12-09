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

namespace Valkyrja\Orm;

/**
 * Interface SoftDeleteEntity.
 *
 * @author Melech Mizrachi
 */
interface SoftDeleteEntity extends Entity
{
    /**
     * Get the formatted current date/time as a save-able string.
     *
     * @return string
     */
    public static function getFormattedDeletedDate(): string;

    /**
     * Get the deleted flag field.
     *
     * @return string
     */
    public static function getIsDeletedField(): string;

    /**
     * Get the date deleted field.
     *
     * @return string
     */
    public static function getDateDeletedField(): string;
}
