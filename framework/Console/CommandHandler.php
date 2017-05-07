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
use Valkyrja\Console\Input\Argument;
use Valkyrja\Console\Input\Option;
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
    protected const TAB        = '  ';
    protected const DOUBLE_TAB = self::TAB . self::TAB;

    /**
     * Help docs for this command.
     *
     * @return int
     */
    public function help(): int
    {
        $this->usageMessage();
        $this->argumentsSection();
        $this->optionsSection();

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
        output()->writeMessage('', PHP_EOL);
    }

    /**
     * The application message.
     *
     * @return void
     */
    protected function applicationMessage(): void
    {
        output()->formatter()->magenta();
        output()->writeMessage('Valkyrja Application');
        output()->formatter()->resetColor();
        output()->writeMessage(' version ');
        output()->formatter()->cyan();
        output()->writeMessage(Application::VERSION, true);
        output()->formatter()->resetColor();
    }

    /**
     * The section message.
     *
     * @param string $sectionName
     *
     * @return void
     */
    protected function sectionTitleMessage(string $sectionName): void
    {
        output()->formatter()->underscore();
        output()->writeMessage($sectionName . ':', true);
        output()->formatter()->resetOptions();
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
        $message = $message ?? $this->usagePath();

        $this->sectionTitleMessage('Usage');
        output()->writeMessage(static::TAB);
        output()->writeMessage($message, true);
    }

    /**
     * Get the usage path.
     *
     * @return string
     */
    protected function usagePath(): string
    {
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

        return $message;
    }

    /**
     * The arguments section.
     *
     * @param \Valkyrja\Console\Input\Argument[] ...$arguments The argument
     *
     * @return void
     */
    protected function argumentsSection(Argument ...$arguments): void
    {
        if (! $arguments) {
            $arguments = $this->getArguments();
        }

        if (! $arguments) {
            return;
        }

        $longestLength = 0;

        $this->sectionDivider();
        $this->sectionTitleMessage('Arguments');

        foreach ($arguments as $argument) {
            $longestLength = max(strlen($argument->getName()), $longestLength);
        }

        foreach ($arguments as $argument) {
            $this->sectionMessage(static::TAB . $argument->getName(), $argument->getDescription(), $longestLength);
        }
    }

    /**
     * The options section.
     *
     * @param \Valkyrja\Console\Input\Option[] ...$options The options
     *
     * @return void
     */
    protected function optionsSection(Option ...$options): void
    {
        if (! $options) {
            $options = $this->getOptions();
        }

        if (! $options) {
            return;
        }

        $longestLength = 0;

        $this->sectionDivider();
        $this->sectionTitleMessage('Options');

        foreach ($options as $option) {
            $longestLength = max(strlen($this->getOptionName($option)), $longestLength);
        }

        foreach ($options as $option) {
            $this->sectionMessage($this->getOptionName($option), $option->getDescription(), $longestLength);
        }
    }

    /**
     * Get an options name for the options section.
     *
     * @param \Valkyrja\Console\Input\Option $option The option
     *
     * @return string
     */
    protected function getOptionName(Option $option): string
    {
        $name = '';

        if ($option->getShortcut()) {
            $name .= '-' . $option->getShortcut() . ', ';
        } else {
            $name .= static::DOUBLE_TAB;
        }

        $name .= '--' . $option->getName();

        return $name;
    }

    /**
     * The section message.
     *
     * @param string $name          The name
     * @param string $description   The description
     * @param int    $longestLength The longest item length
     *
     * @return void
     */
    protected function sectionMessage(string $name, string $description, int $longestLength = null): void
    {
        $longestLength = $longestLength ?? 0;
        $spacesToAdd = $longestLength - strlen($name);

        output()->formatter()->green();
        output()->writeMessage(static::TAB . $name);
        output()->formatter()->resetColor();
        output()->writeMessage($spacesToAdd > 0 ? str_repeat('.', $spacesToAdd) : '');
        output()->writeMessage(str_repeat('.', 8));
        output()->writeMessage($description, true);
    }
}
