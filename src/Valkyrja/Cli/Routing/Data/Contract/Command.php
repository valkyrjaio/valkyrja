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
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddleware;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddleware;
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
     * Get the command matched middleware.
     *
     * @return class-string<CommandMatchedMiddleware>[]
     */
    public function getCommandMatchedMiddleware(): array;

    /**
     * Create a new command with the specified command matched middleware.
     *
     * @param class-string<CommandMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command matched middleware.
     *
     * @param class-string<CommandMatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Get the command dispatched middleware.
     *
     * @return class-string<CommandDispatchedMiddleware>[]
     */
    public function getCommandDispatchedMiddleware(): array;

    /**
     * Create a new command with the specified command dispatched middleware.
     *
     * @param class-string<CommandDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withCommandDispatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command dispatched middleware.
     *
     * @param class-string<CommandDispatchedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedCommandDispatchedMiddleware(string ...$middleware): static;

    /**
     * Get the throwable caught middleware.
     *
     * @return class-string<ThrowableCaughtMiddleware>[]
     */
    public function getThrowableCaughtMiddleware(): array;

    /**
     * Create a new command with the specified throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Get the exited middleware.
     *
     * @return class-string<ExitedMiddleware>[]
     */
    public function getExitedMiddleware(): array;

    /**
     * Create a new command with the specified exited middleware.
     *
     * @param class-string<ExitedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withExitedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added exited middleware.
     *
     * @param class-string<ExitedMiddleware> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedExitedMiddleware(string ...$middleware): static;

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
