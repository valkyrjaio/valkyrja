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

namespace Valkyrja\Orm\Entity\Trait;

use Valkyrja\Orm\Constant\DateFormat;
use Valkyrja\Orm\Support\Helpers;

trait Dateable
{
    /**
     * @inheritDoc
     */
    public static function getDateFormat(): string
    {
        return DateFormat::DEFAULT;
    }

    /**
     * @inheritDoc
     */
    public static function getFormattedDate(): string
    {
        return Helpers::getFormattedDate(static::getDateFormat());
    }

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
