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
 * Trait DatedEntityTrait.
 *
 * @author Melech Mizrachi
 */
trait DatedEntityTrait
{
    /**
     * @inheritDoc
     */
    public static function getDateCreatedField(): string
    {
        return 'date_created';
    }

    /**
     * @inheritDoc
     */
    public static function getDateModifiedField(): string
    {
        return 'date_modified';
    }
}
