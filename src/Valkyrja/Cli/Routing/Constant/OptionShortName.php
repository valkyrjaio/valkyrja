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

namespace Valkyrja\Cli\Routing\Constant;

final class OptionShortName
{
    /** @var non-empty-string */
    public const string HELP = 'h';
    /** @var non-empty-string */
    public const string VERSION = 'v';
    /** @var non-empty-string */
    public const string QUIET = 'q';
    /** @var non-empty-string */
    public const string SILENT = 's';
    /** @var non-empty-string */
    public const string NO_INTERACTION = 'N';
    /** @var non-empty-string */
    public const string TOKEN = 't';
}
