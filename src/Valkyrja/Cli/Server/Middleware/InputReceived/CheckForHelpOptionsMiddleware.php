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

namespace Valkyrja\Cli\Server\Middleware\InputReceived;

use Override;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;
use Valkyrja\Cli\Interaction\Option\Option;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Middleware\Contract\InputReceivedMiddlewareContract;
use Valkyrja\Cli\Middleware\Handler\Contract\InputReceivedHandlerContract;

class CheckForHelpOptionsMiddleware implements InputReceivedMiddlewareContract
{
    /**
     * @param non-empty-string $commandName     The command name to route to
     * @param non-empty-string $optionName      The option name to check for
     * @param non-empty-string $optionShortName The option short name to check for
     */
    public function __construct(
        protected string $commandName,
        protected string $optionName,
        protected string $optionShortName,
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function inputReceived(InputContract $input, InputReceivedHandlerContract $handler): InputContract|OutputContract
    {
        // Check if the options are set for help
        if (
            $input->hasOption($this->optionShortName)
            || $input->hasOption($this->optionName)
        ) {
            $input = $input
                ->withCommandName($this->commandName)
                ->withOptions(
                    new Option('command', $input->getCommandName()),
                );
        }

        return $handler->inputReceived($input);
    }
}
