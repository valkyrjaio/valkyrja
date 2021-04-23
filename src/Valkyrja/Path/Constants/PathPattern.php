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

namespace Valkyrja\Path\Constants;

/**
 * Constant PathPattern.
 *
 * @author Melech Mizrachi
 */
final class PathPattern
{
    public const NUM                  = 'num';
    public const ID                   = 'id';
    public const SLUG                 = 'slug';
    public const UUID                 = 'uuid';
    public const ALPHA                = 'alpha';
    public const ALPHA_LOWERCASE      = 'alpha-lowercase';
    public const ALPHA_UPPERCASE      = 'alpha-uppercase';
    public const ALPHA_NUM            = 'alpha-num';
    public const ALPHA_NUM_UNDERSCORE = 'alpha-num-underscore';
}
