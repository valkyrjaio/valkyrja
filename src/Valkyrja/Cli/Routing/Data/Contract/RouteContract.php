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
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;

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
     */
    public function withDescription(string $description): static;

    /**
     * Get the help text.
     */
    public function getHelpText(): MessageContract;

    /**
     * Create a new Command with the specified help text.
     *
     * @param MessageContract $helpText The help text
     */
    public function withHelpText(MessageContract $helpText): static;

    /**
     * Determine if the command has arguments.
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
     */
    public function getArgument(string $name): ArgumentParameterContract|null;

    /**
     * Create a new Command with the specified argument parameters.
     *
     * @param ArgumentParameterContract ...$arguments The argument parameters
     */
    public function withArguments(ArgumentParameterContract ...$arguments): static;

    /**
     * Create a new Command with added argument parameters.
     *
     * @param ArgumentParameterContract ...$arguments The argument parameters
     */
    public function withAddedArguments(ArgumentParameterContract ...$arguments): static;

    /**
     * Determine if the command has options.
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
     */
    public function getOption(string $name): OptionParameterContract|null;

    /**
     * Create a new Command with the specified option parameters.
     *
     * @param OptionParameterContract ...$options The option parameters
     */
    public function withOptions(OptionParameterContract ...$options): static;

    /**
     * Create a new Command with added option parameters.
     *
     * @param OptionParameterContract ...$options The option parameters
     */
    public function withAddedOptions(OptionParameterContract ...$options): static;

    /**
     * Get the command matched middleware.
     *
     * @return class-string<RouteMatchedMiddlewareContract>[]
     */
    public function getCommandMatchedMiddleware(): array;

    /**
     * Create a new command with the specified command matched middleware.
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    public function withCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command matched middleware.
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    public function withAddedCommandMatchedMiddleware(string ...$middleware): static;

    /**
     * Get the command dispatched middleware.
     *
     * @return class-string<RouteDispatchedMiddlewareContract>[]
     */
    public function getCommandDispatchedMiddleware(): array;

    /**
     * Create a new command with the specified command dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
     */
    public function withCommandDispatchedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added command dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
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
     */
    public function withThrowableCaughtMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
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
     */
    public function withExitedMiddleware(string ...$middleware): static;

    /**
     * Create a new command with added exited middleware.
     *
     * @param class-string<ExitedMiddlewareContract> ...$middleware The middleware
     */
    public function withAddedExitedMiddleware(string ...$middleware): static;

    /**
     * Get the dispatch.
     */
    public function getDispatch(): MethodDispatchContract;

    /**
     * Create a new Command with the specified dispatch.
     *
     * @param MethodDispatchContract $dispatch The dispatch
     */
    public function withDispatch(MethodDispatchContract $dispatch): static;
}
