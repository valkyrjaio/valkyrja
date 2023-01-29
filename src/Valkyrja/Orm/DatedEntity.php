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
 * Interface DatedEntity.
 *
 * @author Melech Mizrachi
 */
interface DatedEntity extends Entity
{
    /**
     * Get the format for the created and modified date.
     *
     * @return string
     */
    public static function getDateFormat(): string;

    /**
     * Get the formatted current date/time as a save-able string.
     *
     * @return string
     */
    public static function getFormattedDate(): string;

    /**
     * Get the date created field.
     *
     * @return string
     */
    public static function getDateCreatedField(): string;

    /**
     * Get the date modified field.
     *
     * @return string
     */
    public static function getDateModifiedField(): string;
}
