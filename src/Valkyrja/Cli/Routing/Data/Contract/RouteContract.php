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

use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Middleware\Contract\CommandDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\CommandMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;

/**
 * Interface RouteContract.
 */
interface RouteContract
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
     * @return MessageContract
     */
    public function getHelpText(): MessageContract;

    /**
     * Create a new Command with the specified help text.
     *
     * @param MessageContract $helpText The help text
     *
     * @return static
     */
    public function withHelpText(MessageContract $helpText): static;

    /**
     * Determine if the command has arguments.
     *
     * @return bool
     */
    public function hasArguments(): bool;

    /**
     * Get the arguments.
     *
     * @return ArgumentParameterContract[]
     */
    public function getArguments(): array;

    /**
     * Get an argument.
     *
     * @param string $name The name
     *
     * @return ArgumentParameterContract|null
     */
    public function getArgument(string $name): ArgumentParameterContract|null;

    /**
     * Create a new Command with the specified argument parameters.
     *
     * @param ArgumentParameterContract ...$arguments The argument parameters
     *
     * @return static
     */
    public function withArguments(ArgumentParameterContract ...$arguments): static;

    /**
     * Create a new Command with added argument parameters.
     *
     * @param ArgumentParameterContract ...$arguments The argument parameters
     *
     * @return static
     */
    public function withAddedArguments(ArgumentParameterContract ...$arguments): static;

    /**
     * Determine if the command has options.
     *
     * @return bool
     */
    public function hasOptions(): bool;

    /**
     * Get the option parameters.
     *
     * @return OptionParameterContract[]
     */
    public function getOptions(): array;

    /**
     * Get an option parameter by name.
     *
     * @param string $name The option name
     *
     * @return OptionParameterContract|null
     */
    public function getOption(string $name): OptionParameterContract|null;

    /**
     * Create a new Command with the specified option parameters.
     *
     * @param OptionParameterContract ...$options The option parameters
     *
     * @return static
     */
    public function withOptions(OptionParameterContract ...$options): static;

    /**
     * Create a new Command with added option parameters.
     *
     * @param OptionParameterContract ...$options The option parameters
     *
     * @return static
     */
    public function withAddedOptions(OptionParameterContract ...$options): static;

    /**
     * Get the command matched middleware.
     *
     * @return class-string<CommandMatchedMiddlewareContract>[]
     */
    public function getCommandMatchedMiddleware(): array;

    /**
     * Create a new command with the specified command matched middleware.
     *
     * @param class-string<CommandMatchedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command matched middleware.
     *
     * @param class-string<CommandMatchedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Get the command dispatched middleware.
     *
     * @return class-string<CommandDispatchedMiddlewareContract>[]
     */
    public function getCommandDispatchedMiddleware(): array;

    /**
     * Create a new command with the specified command dispatched middleware.
     *
     * @param class-string<CommandDispatchedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withCommandDispatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command dispatched middleware.
     *
     * @param class-string<CommandDispatchedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedCommandDispatchedMiddleware(string ...$middleware): static;

    /**
     * Get the throwable caught middleware.
     *
     * @return class-string<ThrowableCaughtMiddlewareContract>[]
     */
    public function getThrowableCaughtMiddleware(): array;

    /**
     * Create a new command with the specified throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Get the exited middleware.
     *
     * @return class-string<ExitedMiddlewareContract>[]
     */
    public function getExitedMiddleware(): array;

    /**
     * Create a new command with the specified exited middleware.
     *
     * @param class-string<ExitedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withExitedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added exited middleware.
     *
     * @param class-string<ExitedMiddlewareContract> ...$middleware The middleware
     *
     * @return static
     */
    public function withAddedExitedMiddleware(string ...$middleware): static;

    /**
     * Get the dispatch.
     *
     * @return MethodDispatchContract
     */
    public function getDispatch(): MethodDispatchContract;

    /**
     * Create a new Command with the specified dispatch.
     *
     * @param MethodDispatchContract $dispatch The dispatch
     *
     * @return static
     */
    public function withDispatch(MethodDispatchContract $dispatch): static;
}
