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

namespace Valkyrja\Routing\Constants;

/**
 * Constant ParameterName.
 *
 * @author Melech Mizrachi
 */
final class ParameterName
{
    public const ALPHA                = 'alpha';
    public const ALPHA_LOWERCASE      = self::ALPHA . 'Lowercase';
    public const ALPHA_NUM            = self::ALPHA . 'Num';
    public const ALPHA_NUM_UNDERSCORE = self::ALPHA . 'NumUnderscore';
    public const ALPHA_UPPERCASE      = self::ALPHA . 'Uppercase';

    public const ANY = 'any';
    public const NUM = 'num';

    public const ID   = 'id';
    public const UUID = 'uuid';
    public const SLUG = 'slug';
}
