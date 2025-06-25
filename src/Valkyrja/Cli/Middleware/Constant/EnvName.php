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

namespace Valkyrja\Cli\Middleware\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string INPUT_RECEIVED      = 'CLI_MIDDLEWARE_INPUT_RECEIVED';
    public const string COMMAND_MATCHED     = 'CLI_MIDDLEWARE_COMMAND_MATCHED';
    public const string COMMAND_NOT_MATCHED = 'CLI_MIDDLEWARE_COMMAND_NOT_MATCHED';
    public const string COMMAND_DISPATCHED  = 'CLI_MIDDLEWARE_COMMAND_DISPATCHED';
    public const string THROWABLE_CAUGHT    = 'CLI_MIDDLEWARE_THROWABLE_CAUGHT';
    public const string EXITED              = 'CLI_MIDDLEWARE_EXITED';
}
