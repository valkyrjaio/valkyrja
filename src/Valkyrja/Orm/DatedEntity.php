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

/**
 * Interface DatedEntity.
 *
 * @author Melech Mizrachi
 */
interface DatedEntity extends Entity
{
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
