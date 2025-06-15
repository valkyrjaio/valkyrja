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

namespace Valkyrja\Console\Commander;

use Valkyrja\Application\Contract\Application;
use Valkyrja\Console\Commander\Contract\Commander as Contract;
use Valkyrja\Console\Constant\ExitCode;
use Valkyrja\Console\Enum\ArgumentMode;
use Valkyrja\Console\Input\Argument;
use Valkyrja\Console\Input\Contract\Input as InputContract;
use Valkyrja\Console\Input\Input;
use Valkyrja\Console\Input\Option;
use Valkyrja\Console\Output\Contract\Output as OutputContract;
use Valkyrja\Console\Output\Output;

use function max;
use function str_repeat;
use function strlen;

/**
 * Abstract Class Handler.
 *
 * @author Melech Mizrachi
 */
abstract class Commander implements Contract
{
    /** @var string */
    public const COMMAND = '';
    /** @var string */
    public const PATH = '';
    /** @var string */
    public const SHORT_DESCRIPTION = '';
    /** @var string */
    public const DESCRIPTION = '';

    /**
     * Tabbing structure to use.
     *
     * @var string
     */
    protected const TAB = '  ';

    /**
     * @var string
     */
    protected const DOUBLE_TAB = self::TAB . self::TAB;

    public function __construct(
        protected InputContract $input = new Input(),
        protected OutputContract $output = new Output(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getCommand(): string
    {
        return static::COMMAND;
    }

    /**
     * @inheritDoc
     */
    public static function getPath(): string
    {
        return static::PATH;
    }

    /**
     * @inheritDoc
     */
    public static function getShortDescription(): string
    {
        return static::SHORT_DESCRIPTION;
    }

    /**
     * @inheritDoc
     */
    public static function getDescription(): string
    {
        return static::DESCRIPTION;
    }

    /**
     * @inheritDoc
     */
    public function help(): int
    {
        $this->usageMessage();
        $this->argumentsSection();
        $this->optionsSection();

        return ExitCode::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public function run(): int
    {
        return ExitCode::SUCCESS;
    }

    /**
     * @inheritDoc
     */
    public function version(): int
    {
        $this->applicationMessage();

        return ExitCode::SUCCESS;
    }

    /**
     * The usage message and description.
     *
     * @param string|null $message [optional] The usage to use instead of default
     *
     * @return void
     */
    protected function usageMessage(string|null $message = null): void
    {
        $message ??= $this->usagePath();

        $this->sectionTitleMessage('Usage');
        $this->output->writeMessage(static::TAB);
        $this->output->writeMessage($message, true);
    }

    /**
     * The arguments section.
     *
     * @param Argument ...$arguments The argument
     *
     * @return void
     */
    protected function argumentsSection(Argument ...$arguments): void
    {
        if (empty($arguments)) {
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
     * @param Option ...$options The options
     *
     * @return void
     */
    protected function optionsSection(Option ...$options): void
    {
        if (empty($options)) {
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
     * The section message.
     *
     * @param string $sectionName
     *
     * @return void
     */
    protected function sectionTitleMessage(string $sectionName): void
    {
        $this->output->getFormatter()->underscore();
        $this->output->writeMessage($sectionName . ':', true);
        $this->output->getFormatter()->resetOptions();
    }

    /**
     * Get the valid arguments.
     *
     * @return Argument[]
     */
    protected function getArguments(): array
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
        $this->output->writeMessage('', true);
    }

    /**
     * The section message.
     *
     * @param string   $name          The name
     * @param string   $description   The description
     * @param int|null $longestLength The longest item length
     *
     * @return void
     */
    protected function sectionMessage(string $name, string $description, int|null $longestLength = null): void
    {
        $longestLength ??= 0;
        $spacesToAdd   = $longestLength - strlen($name);

        $this->output->getFormatter()->green();
        $this->output->writeMessage(static::TAB . $name);

        $this->output->getFormatter()->resetColor();

        $this->output->writeMessage($spacesToAdd > 0 ? str_repeat('.', $spacesToAdd) : '');
        $this->output->writeMessage(str_repeat('.', 8));
        $this->output->writeMessage($description, true);
    }

    /**
     * Get the valid options.
     *
     * @return Option[]
     */
    protected function getOptions(): array
    {
        return [];
    }

    /**
     * Get an options name for the options section.
     *
     * @param Option $option The option
     *
     * @return string
     */
    protected function getOptionName(Option $option): string
    {
        $name = '';

        if (($shortcut = $option->getShortcut()) !== null && $shortcut !== '') {
            $name .= '-' . $shortcut . ', ';
        } else {
            $name .= static::DOUBLE_TAB;
        }

        $name .= '--' . $option->getName();

        return $name;
    }

    /**
     * The application message.
     *
     * @return void
     */
    protected function applicationMessage(): void
    {
        $this->output->getFormatter()->magenta();
        $this->output->writeMessage('Valkyrja Application');
        $this->output->getFormatter()->resetColor();
        $this->output->writeMessage(' version ');
        $this->output->getFormatter()->cyan();
        $this->output->writeMessage(Application::VERSION, true);
        $this->output->getFormatter()->resetColor();
    }
}
