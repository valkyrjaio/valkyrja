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

namespace Valkyrja\Cli\Middleware\InputReceived;

use Override;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class CheckForVersionOptionsMiddleware implements InputReceivedMiddlewareContract
{
    public function __construct(
        protected Env $env,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inputReceived(InputContract $input, InputReceivedHandlerContract $handler): InputContract|OutputContract
    {
        $env = $this->env;
        /** @var non-empty-string $name */
        $name = $env::CLI_VERSION_OPTION_NAME;
        /** @var non-empty-string $shortName */
        $shortName = $env::CLI_VERSION_OPTION_SHORT_NAME;

        // Check if the options are set for the version
        if (
            $input->hasOption($shortName)
            || $input->hasOption($name)
        ) {
            /** @var non-empty-string $commandName */
            $commandName = $env::CLI_VERSION_COMMAND_NAME;

            $input = $input->withCommandName($commandName);
        }

        return $handler->inputReceived($input);
    }
}
