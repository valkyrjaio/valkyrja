<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Console\Enums\ArgumentMode;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Console\CommandHandler as CommandHandlerContract;

/**
 * Abstract Class CommandHandler
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
abstract class CommandHandler implements CommandHandlerContract
{
    /**
     * The command.
     */
    public const COMMAND           = '';
    public const SHORT_DESCRIPTION = '';
    public const DESCRIPTION       = '';

    /**
     * Tabbing structure to use.
     */
    protected const SECTION_TAB = '  ';
    protected const COMMAND_TAB = self::SECTION_TAB . self::SECTION_TAB;

    /**
     * Help docs for this command.
     *
     * @return int
     */
    public function help(): int
    {
        $this->usageMessage();
        $this->sectionDivider();

        return 1;
    }

    /**
     * Get the command version.
     *
     * @return int
     */
    public function version(): int
    {
        $this->applicationMessage();
        output()->writeMessage(PHP_EOL);

        return 1;
    }

    /**
     * Get the valid arguments.
     *
     * @return \Valkyrja\Console\Input\Argument[]
     */
    protected function getArguments(): array
    {
        return [];
    }

    /**
     * Get the valid options.
     *
     * @return \Valkyrja\Console\Input\Option[]
     */
    protected function getOptions(): array
    {
        return [];
    }

    /**
     * The sections divider.
     *
     * @return void
     */
    protected function sectionDivider(): void
    {
        output()->writeMessage(PHP_EOL . PHP_EOL);
    }

    /**
     * The application message.
     *
     * @return void
     */
    protected function applicationMessage(): void
    {
        output()->getFormatter()->magenta();
        output()->writeMessage('Valkyrja Application');
        output()->getFormatter()->resetColor();
        output()->writeMessage(' version ');
        output()->getFormatter()->cyan();
        output()->writeMessage(Application::VERSION);
        output()->getFormatter()->resetColor();
    }

    /**
     * The usage message and description.
     *
     * @param string $message [optional] The usage to use instead of default
     *
     * @return void
     */
    protected function usageMessage(string $message = null): void
    {
        if (null === $message) {
            $message = static::COMMAND;

            if ($this->getOptions()) {
                $message .= ' [options]';
            }

            foreach ($this->getArguments() as $argument) {
                $message .= ' '
                    . ($argument->getMode() === ArgumentMode::OPTIONAL ? '[' : '')
                    . '<'
                    . $argument->getName()
                    . '>'
                    . ($argument->getMode() === ArgumentMode::OPTIONAL ? ']' : '');
            }
        }

        output()->getFormatter()->underscore();
        output()->writeMessage('Usage:', true);
        output()->getFormatter()->resetOptions();
        output()->writeMessage(static::SECTION_TAB);
        output()->writeMessage($message);
    }
}
