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
 * Class ConfigName.
 *
 * @author Melech Mizrachi
 */
final class ConfigName
{
    public const string INPUT_RECEIVED      = 'inputReceived';
    public const string COMMAND_MATCHED     = 'commandMatched';
    public const string COMMAND_NOT_MATCHED = 'commandNotMatched';
    public const string COMMAND_DISPATCHED  = 'commandDispatched';
    public const string THROWABLE_CAUGHT    = 'throwableCaught';
    public const string EXITED              = 'exited';
}
