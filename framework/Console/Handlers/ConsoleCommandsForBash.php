<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Handlers;

use Valkyrja\Console\CommandHandler;

/**
 * Class ConsoleCommands
 *
 * @package Valkyrja\Console\Handlers
 *
 * @author  Melech Mizrachi
 */
class ConsoleCommandsForBash extends CommandHandler
{
    /**
     * Run the command.
     *
     * @param string $commandTyped The command typed
     *
     * @return int
     */
    public function run(string $commandTyped = null): int
    {
        $allCommands = array_keys(console()->getNamedCommands());

        if ($commandTyped) {
            $colonAt = strpos($commandTyped, ':');
            $possibleCommands = [];

            foreach ($allCommands as $command) {
                // Return command in result if it starts with $commandTyped
                if (strpos($command, $commandTyped) === 0) {
                    // Colons acts as separators in bash, so return only second part if colon is in commandTyped.
                    $possibleCommands[] = $colonAt ? substr($command, $colonAt + 1) : $command;
                }
            }
        }
        else {
            // Nothing typed, return all
            $possibleCommands = $allCommands;
        }

        $this->output->writeMessage(implode(' ', $possibleCommands));

        return 1;
    }
}
