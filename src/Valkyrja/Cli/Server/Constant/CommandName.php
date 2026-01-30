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

namespace Valkyrja\Cli\Server\Constant;

final class CommandName
{
    /** @var non-empty-string */
    public const string HELP = 'help';
    /** @var non-empty-string */
    public const string LIST = 'list';
    /** @var non-empty-string */
    public const string LIST_BASH = 'list:bash';
    /** @var non-empty-string */
    public const string VERSION = 'version';
}
