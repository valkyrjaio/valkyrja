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

namespace Valkyrja\Cli\Routing\Data;

use Override;
use Valkyrja\Cli\Interaction\Message\Contract\MessageContract;
use Valkyrja\Cli\Middleware\Contract\ExitedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteDispatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\RouteMatchedMiddlewareContract;
use Valkyrja\Cli\Middleware\Contract\ThrowableCaughtMiddlewareContract;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameterContract;
use Valkyrja\Cli\Routing\Data\Contract\RouteContract;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;

class Route implements RouteContract
{
    /**
     * @param non-empty-string                                  $name                      The name
     * @param non-empty-string                                  $description               The description
     * @param MessageContract                                   $helpText                  The help text
     * @param class-string<RouteMatchedMiddlewareContract>[]    $routeMatchedMiddleware    The command matched middleware
     * @param class-string<RouteDispatchedMiddlewareContract>[] $routeDispatchedMiddleware The command dispatched middleware
     * @param class-string<ThrowableCaughtMiddlewareContract>[] $throwableCaughtMiddleware The throwable caught middleware
     * @param class-string<ExitedMiddlewareContract>[]          $exitedMiddleware          The exited middleware
     * @param ArgumentParameterContract[]                       $arguments                 The arguments
     * @param OptionParameterContract[]                         $options                   The options
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected MessageContract $helpText,
        protected MethodDispatchContract $dispatch,
        protected array $routeMatchedMiddleware = [],
        protected array $routeDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $exitedMiddleware = [],
        protected array $arguments = [],
        protected array $options = [],
    ) {
    }

    /**
     * @param array{
     *     name: non-empty-string,
     *     description: non-empty-string,
     *     helpText: MessageContract,
     *     dispatch: MethodDispatchContract,
     *     routeMatchedMiddleware: class-string<RouteMatchedMiddlewareContract>[],
     *     routeDispatchedMiddleware: class-string<RouteDispatchedMiddlewareContract>[],
     *     throwableCaughtMiddleware: class-string<ThrowableCaughtMiddlewareContract>[],
     *     exitedMiddleware: class-string<ExitedMiddlewareContract>[],
     *     arguments: ArgumentParameterContract[],
     *     options: OptionParameterContract[],
     * } $array The array
     */
    public static function __set_state(array $array): static
    {
        return new static(...$array);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDescription(string $description): static
    {
        $new = clone $this;

        $new->description = $description;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHelpText(): MessageContract
    {
        return $this->helpText;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHelpText(MessageContract $helpText): static
    {
        $new = clone $this;

        $new->helpText = $helpText;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasArguments(): bool
    {
        return $this->arguments !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArgument(string $name): ArgumentParameterContract|null
    {
        $arguments = array_filter($this->arguments, static fn (ArgumentParameterContract $argument) => $argument->getName() === $name);

        return reset($arguments) ?: null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withArguments(ArgumentParameterContract ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedArguments(ArgumentParameterContract ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = [
            ...$this->arguments,
            ...$arguments,
        ];

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasOptions(): bool
    {
        return $this->options !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOption(string $name): OptionParameterContract|null
    {
        $options = array_filter($this->options, static fn (OptionParameterContract $option) => $option->getName() === $name);

        return reset($options) ?: null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withOptions(OptionParameterContract ...$options): static
    {
        $new = clone $this;

        $new->options = $options;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedOptions(OptionParameterContract ...$options): static
    {
        $new = clone $this;

        $new->options = [
            ...$this->options,
            ...$options,
        ];

        return $new;
    }

    /**
     * Get the command matched middleware.
     *
     * @return class-string<RouteMatchedMiddlewareContract>[]
     */
    #[Override]
    public function getRouteMatchedMiddleware(): array
    {
        return $this->routeMatchedMiddleware;
    }

    /**
     * Create a new command with the specified command matched middleware.
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * Create a new command with added command matched middleware.
     *
     * @param class-string<RouteMatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedRouteMatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeMatchedMiddleware = array_merge($this->routeMatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * Get the command dispatched middleware.
     *
     * @return class-string<RouteDispatchedMiddlewareContract>[]
     */
    #[Override]
    public function getRouteDispatchedMiddleware(): array
    {
        return $this->routeDispatchedMiddleware;
    }

    /**
     * Create a new command with the specified command dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = $middleware;

        return $new;
    }

    /**
     * Create a new command with added command dispatched middleware.
     *
     * @param class-string<RouteDispatchedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedRouteDispatchedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->routeDispatchedMiddleware = array_merge($this->routeDispatchedMiddleware, $middleware);

        return $new;
    }

    /**
     * Get the throwable caught middleware.
     *
     * @return class-string<ThrowableCaughtMiddlewareContract>[]
     */
    #[Override]
    public function getThrowableCaughtMiddleware(): array
    {
        return $this->throwableCaughtMiddleware;
    }

    /**
     * Create a new command with the specified throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = $middleware;

        return $new;
    }

    /**
     * Create a new command with added throwable caught middleware.
     *
     * @param class-string<ThrowableCaughtMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedThrowableCaughtMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->throwableCaughtMiddleware = array_merge($this->throwableCaughtMiddleware, $middleware);

        return $new;
    }

    /**
     * Get the exited middleware.
     *
     * @return class-string<ExitedMiddlewareContract>[]
     */
    #[Override]
    public function getExitedMiddleware(): array
    {
        return $this->exitedMiddleware;
    }

    /**
     * Create a new command with the specified exited middleware.
     *
     * @param class-string<ExitedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withExitedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->exitedMiddleware = $middleware;

        return $new;
    }

    /**
     * Create a new command with added exited middleware.
     *
     * @param class-string<ExitedMiddlewareContract> ...$middleware The middleware
     */
    #[Override]
    public function withAddedExitedMiddleware(string ...$middleware): static
    {
        $new = clone $this;

        $new->exitedMiddleware = array_merge($this->exitedMiddleware, $middleware);

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getDispatch(): MethodDispatchContract
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withDispatch(MethodDispatchContract $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
