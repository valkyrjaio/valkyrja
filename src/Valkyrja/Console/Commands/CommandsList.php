<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Commands;

use Valkyrja\Console\Command;
use Valkyrja\Console\Enums\ExitCode;
use Valkyrja\Console\Handlers\Handler;
use Valkyrja\Console\Inputs\Argument;
use Valkyrja\Console\Support\ProvidesCommand;

/**
 * Class ConsoleCommands.
 *
 * @author Melech Mizrachi
 */
class CommandsList extends Handler
{
    use ProvidesCommand;

    /**
     * The command.
     */
    public const COMMAND           = 'commands';
    public const PATH              = '[' . self::COMMAND . '][ {namespace:[a-zA-Z0-9]+}]';
    public const SHORT_DESCRIPTION = 'List all the commands';
    public const DESCRIPTION       = 'List all the commands';

    /**
     * Run the command.
     *
     * @param string $namespace [optional] The namespace to show commands for
     *
     * @return int
     */
    public function run(string $namespace = null): int
    {
        $commands        = console()->all();
        $longestLength   = 0;
        $previousSection = '';

        $this->filterCommands($commands, $longestLength, $namespace);

        $this->applicationMessage();
        $this->sectionDivider();

        $this->usageMessage('command [options] [arguments]');

        $this->optionsSection(...input()->getGlobalOptions());
        $this->sectionDivider();

        $this->sectionTitleMessage('Commands' . ($namespace ? " for the \"{$namespace}\" namespace" : ''));

        /** @var Command $command */
        foreach ($commands as $command) {
            if (null === $namespace) {
                $this->commandSection($command, $previousSection);
            }

            $this->sectionMessage(
                (! $namespace ? static::TAB : '') . $command->getName(),
                $command->getDescription(),
                $longestLength + 2
            );
        }

        return ExitCode::SUCCESS;
    }

    /**
     * Get the valid arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            new Argument('namespace', 'The namespace to list commands for'),
        ];
    }

    /**
     * Filter the commands by type and name.
     *
     * @param array  $commands      The commands
     * @param int    $longestLength The longest length
     * @param string $namespace     [optional] The namespace to show commands
     *                              for
     *
     * @return void
     */
    protected function filterCommands(array &$commands, int &$longestLength, string $namespace = null): void
    {
        $globalCommands = [];

        /** @var Command $command */
        foreach ($commands as $key => $command) {
            $parts            = explode(':', $command->getName());
            $commandName      = $parts[1] ?? null;
            $commandNamespace = $commandName ? $parts[0] : 'global';

            // If there was a namespace passed to the command (commands
            // namespace)  and the namespace for this command doesn't match
            // what was passed then get rid of it so only commands in the
            // namespace are shown.
            if ($commandNamespace !== $namespace && null !== $namespace) {
                unset($commands[$key]);

                continue;
            }

            $longestLength = max(strlen($command->getName()), $longestLength);

            // If this is a global namespaced command
            if ('global' === $commandNamespace) {
                // Set it in the global commands array so when we show the list
                // of commands global commands will be at the top
                $globalCommands[] = $command;

                // Unset from the commands list to avoid duplicates
                unset($commands[$key]);
            }
        }

        // Sort the global commands by name
        $this->sortCommands($globalCommands);
        // Sort the rest of the commands by name
        $this->sortCommands($commands);

        // Set the commands as the merged results of the global and other
        // commands with the global commands at the top of the list
        $commands = array_merge($globalCommands, $commands);
    }

    /**
     * Sort commands by name.
     *
     * @param array $commands The commands
     *
     * @return void
     */
    protected function sortCommands(array &$commands): void
    {
        usort(
            $commands,
            static function (Command $item1, Command $item2) {
                return $item1->getName() <=> $item2->getName();
            }
        );
    }

    /**
     * The command section.
     *
     * @param Command $command         The current command
     * @param string  $previousSection The previous section
     *
     * @return void
     */
    protected function commandSection(Command $command, string &$previousSection): void
    {
        $parts          = explode(':', $command->getName());
        $commandName    = $parts[1] ?? null;
        $currentSection = $commandName ? $parts[0] : 'global';

        if ($previousSection !== $currentSection) {
            output()->formatter()->cyan();
            output()->writeMessage(static::TAB);
            output()->writeMessage($currentSection, true);

            $previousSection = $currentSection;
        }
    }
}
