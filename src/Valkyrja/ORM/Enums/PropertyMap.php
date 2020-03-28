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

use Valkyrja\Enum\Enums\Enum;

/**
 * Enum PropertyMap.
 *
 * @author Melech Mizrachi
 */
final class PropertyMap extends Enum
{
    public const ORDER_BY      = 'orderBy';
    public const LIMIT         = 'limit';
    public const OFFSET        = 'offset';
    public const COLUMNS       = 'columns';
    public const GET_RELATIONS = 'getRelations';

    protected static ?array $VALUES = [
        self::ORDER_BY,
        self::LIMIT,
        self::OFFSET,
        self::COLUMNS,
        self::GET_RELATIONS,
    ];
}
