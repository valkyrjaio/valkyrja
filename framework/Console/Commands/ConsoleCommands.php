<?php

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
use Valkyrja\Console\CommandHandler;
use Valkyrja\Console\Input\Argument;

/**
 * Class ConsoleCommands
 *
 * @package Valkyrja\Console\Commands
 *
 * @author  Melech Mizrachi
 */
class ConsoleCommands extends CommandHandler
{
    /**
     * The command.
     */
    public const COMMAND           = 'commands';
    public const SHORT_DESCRIPTION = '';
    public const DESCRIPTION       = '';

    /**
     * Tabbing structure to use.
     */
    protected const SECTION_TAB = '  ';
    protected const COMMAND_TAB = self::SECTION_TAB . self::SECTION_TAB;

    /**
     * Run the command.
     *
     * @param string $namespace [optional] The namespace to show commands for
     *
     * @return int
     */
    public function run(string $namespace = null): int
    {
        $commands = console()->getCacheable()['commands'];
        $longestLength = 0;
        $previousSection = '';

        $this->filterCommands($commands, $longestLength, $namespace);

        $this->applicationMessage();
        $this->sectionDivider();
        $this->usageMessage('command [options] [arguments]');
        $this->sectionDivider();
        $this->commandsSectionMessage();

        /** @var \Valkyrja\Console\Command $command */
        foreach ($commands as $command) {
            $this->commandSection($command, $previousSection);
            $this->commandMessage($command, $longestLength);
        }

        return 1;
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
     * @param string $namespace     [optional] The namespace to show commands for
     *
     * @return void
     */
    protected function filterCommands(array &$commands, int &$longestLength, string $namespace = null): void
    {
        $globalCommands = [];

        /** @var \Valkyrja\Console\Command $command */
        foreach ($commands as $key => $command) {
            $parts = explode(':', $command->getName());
            $commandName = $parts[1] ?? null;
            $commandNamespace = $commandName ? $parts[0] : 'global';

            if ($commandNamespace !== $namespace && null !== $namespace) {
                unset($commands[$key]);

                continue;
            }

            if ($longestLength < $nameLength = strlen($command->getName())) {
                $longestLength = $nameLength;
            }

            if (null === $commandNamespace) {
                $globalCommands[] = $command;

                unset($commands[$key]);
            }
        }

        $this->sortCommands($commands);

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
        usort($commands, function (Command $item1, Command $item2) {
            return $item1->getName() <=> $item2->getName();
        });
    }

    /**
     * The commands section message.
     *
     * @return void
     */
    protected function commandsSectionMessage(): void
    {
        output()->getFormatter()->underscore();
        output()->writeMessage('Commands:', true);
        output()->getFormatter()->resetOptions();
    }

    /**
     * The command section.
     *
     * @param \Valkyrja\Console\Command $command         The current command
     * @param string                    $previousSection The previous section
     *
     * @return void
     */
    protected function commandSection(Command $command, string &$previousSection): void
    {
        $parts = explode(':', $command->getName());
        $commandName = $parts[1] ?? null;
        $currentSection = $commandName ? $parts[0] : 'global';

        if ($previousSection !== $currentSection) {
            output()->getFormatter()->cyan();
            output()->writeMessage(static::SECTION_TAB);
            output()->writeMessage($currentSection, true);

            $previousSection = $currentSection;
        }
    }

    /**
     * The command message.
     *
     * @param \Valkyrja\Console\Command $command       The command
     * @param int                       $longestLength The longest length
     *
     * @return void
     */
    protected function commandMessage(Command $command, int $longestLength): void
    {
        $spacesToAdd = $longestLength - strlen($command->getName());

        output()->getFormatter()->green();
        output()->writeMessage(static::COMMAND_TAB);
        output()->writeMessage($command->getName());
        output()->getFormatter()->resetColor();
        output()->writeMessage($spacesToAdd > 0 ? str_repeat('.', $spacesToAdd) : '');
        output()->writeMessage(str_repeat('.', 8));
        output()->writeMessage($command->getDescription(), true);
    }
}
