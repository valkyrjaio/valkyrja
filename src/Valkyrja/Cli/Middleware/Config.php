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

namespace Valkyrja\Cli\Middleware;

use Valkyrja\Cli\Middleware\Constant\ConfigName;
use Valkyrja\Cli\Middleware\Constant\EnvName;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandNotMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
use Valkyrja\Support\Config as ParentConfig;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends ParentConfig
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::INPUT_RECEIVED      => EnvName::INPUT_RECEIVED,
        ConfigName::COMMAND_MATCHED     => EnvName::COMMAND_MATCHED,
        ConfigName::COMMAND_NOT_MATCHED => EnvName::COMMAND_NOT_MATCHED,
        ConfigName::COMMAND_DISPATCHED  => EnvName::COMMAND_DISPATCHED,
        ConfigName::THROWABLE_CAUGHT    => EnvName::THROWABLE_CAUGHT,
        ConfigName::EXITED              => EnvName::EXITED,
    ];

    /**
     * @param class-string<InputReceivedMiddleware>[]     $inputReceived     The input received middleware
     * @param class-string<CommandMatchedMiddleware>[]    $commandMatched    The command matched middleware
     * @param class-string<CommandNotMatchedMiddleware>[] $commandNotMatched The command not matched middleware
     * @param class-string<CommandDispatchedMiddleware>[] $commandDispatched The command dispatched middleware
     * @param class-string<ThrowableCaughtMiddleware>[]   $throwableCaught   The throwable caught middleware
     * @param class-string<ExitedMiddleware>[]            $exited            The exited middleware
     */
    public function __construct(
        public array $inputReceived = [],
        public array $commandDispatched = [],
        public array $commandMatched = [],
        public array $commandNotMatched = [],
        public array $throwableCaught = [],
        public array $exited = [],
    ) {
    }
}
