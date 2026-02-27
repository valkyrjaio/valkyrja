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
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidArgumentNameException;
use Valkyrja\Cli\Routing\Throwable\Exception\InvalidOptionNameException;
use Valkyrja\Cli\Routing\Throwable\Exception\NoHelpTextException;
use Valkyrja\Dispatch\Data\Contract\MethodDispatchContract;

use function is_array;

class Route implements RouteContract
{
    /**
     * The help text callable.
     *
     * @var (callable():MessageContract)|null
     */
    protected $helpText;

    /**
     * @param non-empty-string                                  $name                      The name
     * @param non-empty-string                                  $description               The description
     * @param (callable():MessageContract)|null                 $helpText                  The help text
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
        protected MethodDispatchContract $dispatch,
        callable|null $helpText = null,
        protected array $routeMatchedMiddleware = [],
        protected array $routeDispatchedMiddleware = [],
        protected array $throwableCaughtMiddleware = [],
        protected array $exitedMiddleware = [],
        protected array $arguments = [],
        protected array $options = [],
    ) {
        $this->validateHelpText($helpText);

        $this->helpText = $helpText;
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
    public function hasHelpText(): bool
    {
        return $this->helpText !== null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHelpText(): callable
    {
        return $this->helpText
            ?? throw new NoHelpTextException('No help text has been set for this route');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getHelpTextMessage(): MessageContract
    {
        $helpText = $this->getHelpText();

        return $helpText();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withHelpText(callable $helpText): static
    {
        $new = clone $this;

        $new->validateHelpText($helpText);

        $new->helpText = $helpText;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutHelpText(): static
    {
        $new = clone $this;

        $new->helpText = null;

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
    public function hasArgument(string $name): bool
    {
        return $this->filterArgumentByName($name) !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getArgument(string $name): ArgumentParameterContract
    {
        $arguments = $this->filterArgumentByName($name);

        return reset($arguments)
            ?: throw new InvalidArgumentNameException("The argument `$name` was not found");
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
    public function hasOption(string $name): bool
    {
        return $this->filterOptionByName($name) !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getOption(string $name): OptionParameterContract
    {
        $options = $this->filterOptionByName($name);

        return reset($options)
            ?: throw new InvalidOptionNameException("The option `$name` was not found");
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

    /**
     * Validate help text.
     *
     * @param (callable():MessageContract)|null $helpText The help text
     */
    protected function validateHelpText(callable|null $helpText = null): void
    {
        if ($helpText !== null && ! is_array($helpText)) {
            throw new InvalidArgumentException('Help text must be a callable array');
        }
    }

    /**
     * Filter the arguments by a given name.
     *
     * @param string $name The name
     *
     * @return ArgumentParameterContract[]
     */
    protected function filterArgumentByName(string $name): array
    {
        return array_filter(
            $this->arguments,
            static fn (ArgumentParameterContract $argument) => $argument->getName() === $name
        );
    }

    /**
     * Filter the options by a given name.
     *
     * @param string $name The name
     *
     * @return OptionParameterContract[]
     */
    protected function filterOptionByName(string $name): array
    {
        return array_filter(
            $this->options,
            static fn (OptionParameterContract $option) => $option->getName() === $name
        );
    }
}
