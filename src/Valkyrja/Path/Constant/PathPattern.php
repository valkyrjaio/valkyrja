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

namespace Valkyrja\Path\Constant;

/**
 * Constant PathPattern.
 *
 * @author Melech Mizrachi
 */
final class PathPattern
{
    public const string NUM                  = 'num';
    public const string ID                   = 'id';
    public const string SLUG                 = 'slug';
    public const string UUID                 = 'uuid';
    public const string ALPHA                = 'alpha';
    public const string ALPHA_LOWERCASE      = 'alpha-lowercase';
    public const string ALPHA_UPPERCASE      = 'alpha-uppercase';
    public const string ALPHA_NUM            = 'alpha-num';
    public const string ALPHA_NUM_UNDERSCORE = 'alpha-num-underscore';
}
