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

namespace Valkyrja\Cli\Command;

use Valkyrja\Cli\Interaction\Enum\ExitCode;
use Valkyrja\Cli\Interaction\Enum\TextColor;
use Valkyrja\Cli\Interaction\Factory\Contract\OutputFactoryContract;
use Valkyrja\Cli\Interaction\Formatter\Formatter;
use Valkyrja\Cli\Interaction\Formatter\HighlightedTextFormatter;
use Valkyrja\Cli\Interaction\Message\Banner;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Interaction\Message\ErrorMessage;
use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Messages;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Interaction\Output\Contract\OutputContract;
use Valkyrja\Cli\Routing\Attribute\Route;
use Valkyrja\Cli\Routing\Collection\Contract\CollectionContract;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract as OptionContract;
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

use function is_string;

/**
 * Class HelpCommand.
 */
class HelpCommand
{
    public const string NAME = 'help';

    #[Route(
        name: self::NAME,
        description: 'Help for a command',
        helpText: new Message('A command to get help for a specific command.'),
        parameters: [
            new OptionParameter(
                name: 'command',
                description: 'The name of the command to get help for',
                valueDisplayName: 'command',
                mode: OptionMode::REQUIRED
            ),
        ]
    )]
    public function run(VersionCommand $version, RouteContract $route, CollectionContract $collection, OutputFactoryContract $outputFactory): OutputContract
    {
        $commandName = $route->getOption('command')?->getFirstValue();

        if (! is_string($commandName)) {
            return $outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage('Command name is required.'))
                );
        }

        $helpCommand = $collection->get($commandName);

        if ($helpCommand === null) {
            return $outputFactory
                ->createOutput()
                ->withExitCode(ExitCode::ERROR)
                ->withAddedMessages(
                    new Banner(new ErrorMessage("Command `$commandName` was not found."))
                );
        }

        $output = $version->run($outputFactory);

        return $this->getHelpText($output, $helpCommand);
    }

    /**
     * Get the help text for a given command.
     */
    protected function getHelpText(OutputContract $output, RouteContract $route): OutputContract
    {
        $argumentMessages = [];
        $optionMessages   = [];

        if ($route->hasOptions()) {
            $optionMessages[] = $this->getOptionsHeadingMessages($route);
            $optionMessages[] = new NewLine();

            foreach ($route->getOptions() as $option) {
                $optionMessages[] = $this->getOptionMessages($option);
            }
        }

        $optionMessages[] = $this->getGlobalOptionsHeadingMessages($route);
        $optionMessages[] = new NewLine();

        $optionMessages[] = $this->getOptionMessages(new QuietOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new SilentOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new NoInteractionOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new HelpOptionParameter());
        $optionMessages[] = $this->getOptionMessages(new VersionOptionParameter());

        if ($route->hasArguments()) {
            $argumentMessages[] = $this->getArgumentsHeadingMessages($route);
            $argumentMessages[] = new NewLine();

            foreach ($route->getArguments() as $argument) {
                $optionMessages[] = $this->getArgumentMessages($argument);
            }

            $argumentMessages[] = new NewLine();
        }

        return $output
            ->withAddedMessages(
                new NewLine(),
                $this->getNameMessages($route),
                new NewLine(),
                new NewLine(),
                $this->getDescriptionMessages($route),
                new NewLine(),
                new NewLine(),
                $this->getUsageMessages($route),
                new NewLine(),
                new NewLine(),
                ...$argumentMessages,
                ...$optionMessages,
            )
            ->withAddedMessages(
                $this->getHelpTextMessages($route),
                new NewLine(),
            )
            ->writeMessages();
    }

    /**
     * Get name messages.
     */
    protected function getNameMessages(RouteContract $route): Messages
    {
        return new Messages(
            new Message('Name: ', new HighlightedTextFormatter()),
            new Message($route->getName()),
        );
    }

    /**
     * Get description messages.
     */
    protected function getDescriptionMessages(RouteContract $route): Messages
    {
        return new Messages(
            new Message('Description:', new HighlightedTextFormatter()),
            new NewLine(),
            $this->getIndentedText(new Message($route->getDescription())),
        );
    }

    /**
     * Get help text messages.
     */
    protected function getHelpTextMessages(RouteContract $route): Messages
    {
        return new Messages(
            new Message('Help:', new HighlightedTextFormatter()),
            new NewLine(),
            $this->getIndentedText($route->getHelpText()),
            new NewLine(),
        );
    }

    /**
     * Get usage messages.
     */
    protected function getUsageMessages(RouteContract $route): Messages
    {
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
    protected function getOptionsHeadingMessages(RouteContract $route): Messages
    {
        return new Messages(
            new Message('Options:', new HighlightedTextFormatter()),
        );
    }

    /**
     * Get global options heading messages.
     */
    protected function getGlobalOptionsHeadingMessages(RouteContract $route): Messages
    {
        return new Messages(
            new Message('Global Options:', new HighlightedTextFormatter()),
        );
    }

    /**
     * Get option messages.
     */
    protected function getOptionMessages(OptionContract $option): Messages
    {
        $optionMessages = [];

        $shortNames       = $option->getShortNames();
        $validValues      = $option->getValidValues();
        $defaultValue     = $option->getDefaultValue();
        $valueDisplayName = $option->getValueDisplayName();

        $optionMessages[] = new Message('  ');
        $optionMessages[] = new Message('--' . $option->getName(), new Formatter(textColor: TextColor::MAGENTA));

        if ($shortNames !== []) {
            $optionMessages[] = new Message(', ');
            $optionMessages[] = new Message('-' . implode('|', $shortNames), new Formatter(textColor: TextColor::MAGENTA));
        }

        if ($valueDisplayName !== null) {
            $optionMessages[] = new Message(' ');

            if ($option->getValueMode() === OptionValueMode::ARRAY) {
                $optionMessages[] = new Message('...', new HighlightedTextFormatter());
            }

            if ($option->getMode() === OptionMode::REQUIRED) {
                $optionMessages[] = new Message('=' . $valueDisplayName, new HighlightedTextFormatter());
            } else {
                $optionMessages[] = new Message('[=' . $valueDisplayName . ']', new HighlightedTextFormatter());
            }
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
    protected function getArgumentsHeadingMessages(RouteContract $route): Messages
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
