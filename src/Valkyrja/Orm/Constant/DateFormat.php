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
 * Constant DateFormat.
 */
final class DateFormat
{
    public const string DEFAULT     = 'm-d-Y H:i:s T';
    public const string MILLISECOND = 'm-d-Y H:i:s.v T';
    public const string MICROSECOND = 'm-d-Y H:i:s.u T';
}
