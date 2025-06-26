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

namespace Valkyrja\Cli\Routing\Data\Contract;

use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;

/**
 * Interface Command.
 *
 * @author Melech Mizrachi
 */
interface Command
{
    /**
     * Get the name.
     *
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * Create a new Command with the specified name.
     *
     * @param non-empty-string $name The name
     *
     * @return static
     */
    public function withName(string $name): static;

    /**
     * Get the description.
     *
     * @return non-empty-string
     */
    public function getDescription(): string;

    /**
     * Create a new Command with the specified description.
     *
     * @param non-empty-string $description The description
     *
     * @return static
     */
    public function withDescription(string $description): static;

    /**
     * Get the help text.
     *
     * @return Message
     */
    public function getHelpText(): Message;

    /**
     * Create a new Command with the specified help text.
     *
     * @param Message $helpText The help text
     *
     * @return static
     */
    public function withHelpText(Message $helpText): static;

    /**
     * Determine if the command has arguments.
     *
     * @return bool
     */
    public function hasArguments(): bool;

    /**
     * Get the arguments.
     *
     * @return ArgumentParameter[]
     */
    public function getArguments(): array;

    /**
     * Get an argument.
     *
     * @param string $name The name
     *
     * @return ArgumentParameter|null
     */
    public function getArgument(string $name): ArgumentParameter|null;

    /**
     * Create a new Command with the specified argument parameters.
     *
     * @param ArgumentParameter ...$arguments The argument parameters
     *
     * @return static
     */
    public function withArguments(ArgumentParameter ...$arguments): static;

    /**
     * Create a new Command with added argument parameters.
     *
     * @param ArgumentParameter ...$arguments The argument parameters
     *
     * @return static
     */
    public function withAddedArguments(ArgumentParameter ...$arguments): static;

    /**
     * Determine if the command has options.
     *
     * @return bool
     */
    public function hasOptions(): bool;

    /**
     * Get the option parameters.
     *
     * @return OptionParameter[]
     */
    public function getOptions(): array;

    /**
     * Get an option parameter by name.
     *
     * @param string $name The option name
     *
     * @return OptionParameter|null
     */
    public function getOption(string $name): OptionParameter|null;

    /**
     * Create a new Command with the specified option parameters.
     *
     * @param OptionParameter ...$options The option parameters
     *
     * @return static
     */
    public function withOptions(OptionParameter ...$options): static;

    /**
     * Create a new Command with added option parameters.
     *
     * @param OptionParameter ...$options The option parameters
     *
     * @return static
     */
    public function withAddedOptions(OptionParameter ...$options): static;

    /**
     * Get the dispatch.
     *
     * @return MethodDispatch
     */
    public function getDispatch(): MethodDispatch;

    /**
     * Create a new Command with the specified dispatch.
     *
     * @param MethodDispatch $dispatch The dispatch
     *
     * @return static
     */
    public function withDispatch(MethodDispatch $dispatch): static;
}
