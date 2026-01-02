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
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class CheckForHelpOptionsMiddleware implements InputReceivedMiddlewareContract
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
        $name = $env::CLI_HELP_OPTION_NAME;
        /** @var non-empty-string $shortName */
        $shortName = $env::CLI_HELP_OPTION_SHORT_NAME;

        // Check if the options are set for help
        if (
            $input->hasOption($shortName)
            || $input->hasOption($name)
        ) {
            /** @var non-empty-string $commandName */
            $commandName = $env::CLI_HELP_COMMAND_NAME;

            $input = $input
                ->withCommandName($commandName)
                ->withOptions(
                    new Option('command', $input->getCommandName()),
                );
        }

        return $handler->inputReceived($input);
    }
}
