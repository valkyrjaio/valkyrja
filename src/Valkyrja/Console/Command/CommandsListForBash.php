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
use Valkyrja\Console\Support\Provides;

use function array_keys;
use function implode;
use function strpos;
use function substr;
use function Valkyrja\console;
use function Valkyrja\output;

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

    /**
     * @inheritDoc
     *
     * @param string|null $commandTyped The command typed
     */
    public function run(string|null $commandTyped = null): int
    {
        $allCommands = array_keys(console()->getNamedCommands());

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

        output()->writeMessage(implode(' ', $possibleCommands));

        return ExitCode::SUCCESS;
    }
}
