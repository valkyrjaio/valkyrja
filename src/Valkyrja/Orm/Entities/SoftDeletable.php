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

namespace Valkyrja\Orm\Entities;

use Valkyrja\Orm\Constants\DateFormat;
use Valkyrja\Orm\Support\Helpers;

/**
 * Trait SoftDeletableEntity.
 *
 * @author Melech Mizrachi
 */
trait SoftDeletable
{
    /**
     * @inheritDoc
     */
    public static function getDeletedDateFormat(): string
    {
        return DateFormat::DEFAULT;
    }

    /**
     * @inheritDoc
     */
    public static function getFormattedDeletedDate(): string
    {
        return Helpers::getFormattedDate(static::getDeletedDateFormat());
    }

    /**
     * @inheritDoc
     */
    public static function getDateDeletedField(): string
    {
        return 'date_deleted';
    }
}
