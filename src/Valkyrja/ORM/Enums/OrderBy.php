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

namespace Valkyrja\ORM\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum OrderBy.
 */
final class OrderBy extends Enum
{
    public const ASC  = 'ASC';
    public const DESC = 'DESC';

    protected static ?array $VALUES = [
        self::ASC,
        self::DESC,
    ];
}
