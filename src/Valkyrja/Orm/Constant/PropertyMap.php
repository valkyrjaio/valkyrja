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

namespace Valkyrja\Orm\Constant;

/**
 * Constant PropertyMap.
 *
 * @author Melech Mizrachi
 */
final class PropertyMap
{
    public const string ORDER_BY      = 'orderBy';
    public const string LIMIT         = 'limit';
    public const string OFFSET        = 'offset';
    public const string COLUMNS       = 'columns';
    public const string GET_RELATIONS = 'getRelations';
}
