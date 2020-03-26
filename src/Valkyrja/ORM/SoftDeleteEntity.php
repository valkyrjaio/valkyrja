<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\ORM;

/**
 * Interface SoftDeleteEntity.
 *
 * @author Melech Mizrachi
 */
interface SoftDeleteEntity extends Entity
{
    /**
     * Get the deleted field.
     *
     * @return string
     */
    public static function getDeletedField(): string;

    /**
     * Get the deleted at field.
     *
     * @return string
     */
    public static function getDeletedAtField(): string;
}
