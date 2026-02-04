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

namespace Valkyrja\Cli\Server\Command;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Format\TextColorFormat;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Messages;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Interaction\Output\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Cli\Routing\Data\Option\HelpOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\NoInteractionOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\QuietOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\SilentOptionParameter;
use Valkyrja\Cli\Routing\Data\Option\VersionOptionParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Enum\ArgumentValueMode;
use Valkyrja\Cli\Routing\Enum\OptionMode;
use Valkyrja\Cli\Routing\Enum\OptionValueMode;
use Valkyrja\Cli\Server\Constant\CommandName;

use function is_string;

class HelpCommand
{
    protected RouteContract $helpRoute;

    public function __construct(
        protected VersionCommand $version,
        protected RouteContract $route,
        protected CollectionContract $collection,
        protected OutputFactoryContract $outputFactory,
    ) {
    }

    /**
     * The help text.
     */
    public static function help(): MessageContract
    {
        return new Message('A command to get help for a specific command.');
    }

    #[Route(
        name: CommandName::HELP,
        description: 'Help for a command',
        helpText: [self::class, 'help'],
        options: [
            new OptionParameter(
                name: 'command',
                description: 'The name of the command to get help for',
                valueDisplayName: 'command',
                mode: OptionMode::REQUIRED
            ),
        ]
    )]
    public function run(): OutputContract
    {
        $commandName = $this->route->getOption('command')?->getFirstValue();

        if (! is_string($commandName)) {
            return $this->outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage('Command name is required.'))
                );
        }

        $helpRoute = $this->collection->get($commandName);

        if ($helpRoute === null) {
            return $this->outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage("Command `$commandName` was not found."))
                );
        }

        $this->helpRoute = $helpRoute;

        $output = $this->version->run();

        return $this->getHelpText($output);
    }

    /**
     * Get the help text for a given command.
     */
    protected function getHelpText(OutputContract $output): OutputContract
    {
        $route            = $this->helpRoute;
        $argumentMessages = [];
        $optionMessages   = [];

        if ($route->hasOptions()) {
            $optionMessages[] = $this->getOptionsHeadingMessages();
            $optionMessages[] = new NewLine();

            foreach ($route->getOptions() as $option) {
                $optionMessages[] = $this->getOptionMessages($option);
            }
        }

        $optionMessages[] = $this->getGlobalOptionsHeadingMessages();
        $optionMessages[] = new NewLine();

        $optionMessages[] = $this->getOptionMessages(new QuietOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new SilentOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new NoInteractionOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new HelpOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new VersionOptionParameter());

        if ($route->hasArguments()) {
            $argumentMessages[] = $this->getArgumentsHeadingMessages();
            $argumentMessages[] = new NewLine();

            foreach ($route->getArguments() as $argument) {
                $argumentMessages[] = $this->getArgumentMessages($argument);
            }
        }

        $output = $output
            ->withAddedMessages(
                new NewLine(),
                $this->getNameMessages(),
                new NewLine(),
                new NewLine(),
                $this->getDescriptionMessages(),
                new NewLine(),
                new NewLine(),
                $this->getUsageMessages(),
                new NewLine(),
                new NewLine(),
                ...$argumentMessages,
                ...$optionMessages,
            );

        if ($this->helpRoute->getHelpTextMessage() !== null) {
            return $output->withAddedMessages(
                $this->getHelpTextMessages(),
                new NewLine(),
            );
        }

        return $output;
    }

    /**
     * Get name messages.
     */
    protected function getNameMessages(): Messages
    {
        return new Messages(
            new Message('Name: ', new HighlightedTextFormatter()),
            new Message($this->helpRoute->getName()),
        );
    }

    /**
     * Get description messages.
     */
    protected function getDescriptionMessages(): Messages
    {
        return new Messages(
            new Message('Description:', new HighlightedTextFormatter()),
            new NewLine(),
            $this->getIndentedText(new Message($this->helpRoute->getDescription())),
        );
    }

    /**
     * Get help text messages.
     */
    protected function getHelpTextMessages(): Messages
    {
        return new Messages(
            new Message('Help:', new HighlightedTextFormatter()),
            new NewLine(),
            $this->getIndentedText($this->helpRoute->getHelpTextMessage()),
            new NewLine(),
        );
    }

    /**
     * Get usage messages.
     */
    protected function getUsageMessages(): Messages
    {
        $route = $this->helpRoute;
        $usage = $route->getName();

        if ($route->hasOptions()) {
            $usage .= ' [options]';
        }

        $usage .= ' [global options]';

        if ($route->hasArguments()) {
            foreach ($route->getArguments() as $argument) {
                $usage .= ' ['
                    . $argument->getName()
                    . ($argument->getValueMode() === ArgumentValueMode::ARRAY ? '...' : '')
                    . ']';
            }
        }

        return new Messages(
            new Message('Usage:', new HighlightedTextFormatter()),
            new NewLine(),
            $this->getIndentedText(new Message($usage)),
        );
    }

    /**
     * Get options heading messages.
     */
    protected function getOptionsHeadingMessages(): Messages
    {
        return new Messages(
            new Message('Options:', new HighlightedTextFormatter()),
        );
    }

    /**
     * Get global options heading messages.
     */
    protected function getGlobalOptionsHeadingMessages(): Messages
    {
        return new Messages(
            new Message('Global Options:', new HighlightedTextFormatter()),
        );
    }

    /**
     * Get option messages.
     */
    protected function getOptionMessages(OptionParameterContract $option): Messages
    {
        $optionMessages = [];

        $shortNames       = $option->getShortNames();
        $validValues      = $option->getValidValues();
        $defaultValue     = $option->getDefaultValue();
        $valueDisplayName = $option->getValueDisplayName();

        $optionMessages[] = new Message('  ');
        $optionMessages[] = new Message('--' . $option->getName(), new Formatter(new TextColorFormat(TextColor::MAGENTA)));

        if ($shortNames !== []) {
            $optionMessages[] = new Message(', ');
            $optionMessages[] = new Message('-' . implode('|', $shortNames), new Formatter(new TextColorFormat(TextColor::MAGENTA)));
        }

        if ($valueDisplayName !== null) {
            $optionMessages[] = new Message(' ');

            $text = '';

            if ($option->getValueMode() === OptionValueMode::ARRAY) {
                $text = '...';
            }

            if ($option->getMode() === OptionMode::REQUIRED) {
                $text .= '=' . $valueDisplayName;
            } else {
                $text .= '[=' . $valueDisplayName . ']';
            }

            $optionMessages[] = new Message($text, new HighlightedTextFormatter());
        }

        $optionMessages[] = new NewLine();
        $optionMessages[] = new Message('    ');
        $optionMessages[] = new Message($option->getDescription());

        if ($validValues !== []) {
            $valueSpacing = "\n      - ";

            $optionMessages[] = new NewLine();
            $optionMessages[] = new NewLine();
            $optionMessages[] = new Message('    ');
            $optionMessages[] = new Message('Valid values:');

            foreach ($validValues as $validValue) {
                $optionMessages[] = new Message($valueSpacing . "`$validValue`");

                if ($validValue === $defaultValue) {
                    $optionMessages[] = new Message(' (default)', new HighlightedTextFormatter());
                }
            }
        }

        $optionMessages[] = new NewLine();
        $optionMessages[] = new NewLine();

        return new Messages(
            ...$optionMessages
        );
    }

    /**
     * Get arguments heading messages.
     */
    protected function getArgumentsHeadingMessages(): Messages
    {
        return new Messages(
            new Message('Arguments:', new HighlightedTextFormatter()),
        );
    }

    /**
     * Get argument messages.
     */
    protected function getArgumentMessages(ArgumentParameterContract $argument): Messages
    {
        $argumentMessages = [];

        $argumentMessages[] = new Message('  ');
        $argumentMessages[] = new Message($argument->getName());
        $argumentMessages[] = new NewLine();
        $argumentMessages[] = new Message('    ');
        $argumentMessages[] = new Message($argument->getDescription());
        $argumentMessages[] = new NewLine();
        $argumentMessages[] = new NewLine();

        return new Messages(
            ...$argumentMessages
        );
    }

    /**
     * Get indented text.
     */
    protected function getIndentedText(MessageContract $message): MessageContract
    {
        $spaces = '  ';

        /** @var non-empty-string $wrappedText */
        $wrappedText = wordwrap(
            $spaces . $message->getText(),
            100,
            "\n$spaces"
        );

        return $message->withText(text: $wrappedText);
    }
}
