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

namespace Valkyrja\Console\Command;

use Valkyrja\Console\Commander\Commander;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Contract\Console;
use Valkyrja\Console\Input\Contract\Input as InputContract;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Output\Contract\Output as OutputContract;
use Valkyrja\Console\Output\Output;
use Valkyrja\Console\Support\Provides;

use function array_keys;
use function implode;
use function strpos;
use function substr;

/**
 * Class CommandsListForBash.
 *
 * @author Melech Mizrachi
 */
class CommandsListForBash extends Commander
{
    use Provides;

    /**
     * The command.
     */
    public const COMMAND           = 'console:commandsForBash';
    public const PATH              = self::COMMAND . ' valkyrja[ {commandTyped:[a-zA-Z0-9\:]+}]';
    public const SHORT_DESCRIPTION = 'List all the commands for bash auto complete';

    public function __construct(
        protected Console $console,
        InputContract $input = new Input(),
        OutputContract $output = new Output()
    ) {
        parent::__construct($input, $output);
    }

    /**
     * @inheritDoc
     *
     * @param string|null $commandTyped The command typed
     */
    public function run(string|null $commandTyped = null): int
    {
        /** @var string[] $allCommands */
        $allCommands = array_keys($this->console->getNamedCommands());

        if ($commandTyped !== null && $commandTyped !== '') {
            $colonAt          = strpos($commandTyped, ':');
            $possibleCommands = [];

            foreach ($allCommands as $command) {
                // Return command in result if it starts with $commandTyped
                if (str_starts_with($command, $commandTyped)) {
                    // Colons acts as separators in bash, so return only second
                    // part if colon is in commandTyped.
                    $possibleCommands[] = $colonAt !== false ? substr($command, $colonAt + 1) : $command;
                }
            }
        } else {
            // Nothing typed, return all
            $possibleCommands = $allCommands;
        }

        $this->output->writeMessage(implode(' ', $possibleCommands));

        return ExitCode::SUCCESS;
    }
}
