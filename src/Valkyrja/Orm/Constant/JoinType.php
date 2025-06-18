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
 * Constant JoinType.
 *
 * @author Melech Mizrachi
 */
final class JoinType
{
    public const string RIGHT      = 'RIGHT';
    public const string LEFT       = 'LEFT';
    public const string INNER      = 'INNER';
    public const string OUTER      = 'OUTER';
    public const string FULL_OUTER = 'FULL OUTER';
}
