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

namespace Valkyrja\ORM\Entities;

/**
 * Trait SoftDeleteEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait SoftDeleteEntityTrait
{
    /**
     * Get the deleted field.
     *
     * @return string
     */
    public static function getDeletedField(): string
    {
        return 'deleted';
    }

    /**
     * Get the date deleted field.
     *
     * @return string
     */
    public static function getDateDeletedField(): string
    {
        return 'date_deleted';
    }
}
