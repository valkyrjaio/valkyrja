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

namespace Valkyrja\Cli\Interaction\Input;

use Override;
use Valkyrja\Cli\Interaction\Argument\Contract\Argument;
use Valkyrja\Cli\Interaction\Input\Contract\Input as Contract;
use Valkyrja\Cli\Interaction\Option\Contract\Option;

/**
 * Class Input.
 *
 * @author Melech Mizrachi
 */
class Input implements Contract
{
    /**
     * @param non-empty-string $caller      The caller (application name)
     * @param non-empty-string $commandName The command name
     * @param Argument[]       $arguments   The arguments
     * @param Option[]         $options     The options
     */
    public function __construct(
        protected string $caller = 'valkyrja',
        protected string $commandName = 'list',
        protected array $arguments = [],
        protected array $options = []
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCaller(): string
    {
        return $this->caller;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCaller(string $caller): static
    {
        $new = clone $this;

        $new->caller = $caller;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getCommandName(): string
    {
        return $this->commandName;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withCommandName(string $commandName): static
    {
        $new = clone $this;

        $new->commandName = $commandName;

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return Argument[]
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
    public function withArguments(Argument ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedArgument(Argument $argument): static
    {
        $new = clone $this;

        $new->arguments[] = $argument;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutArgument(string $value): static
    {
        $new = clone $this;

        $new->arguments = array_filter(
            $this->arguments,
            static fn (Argument $argument): bool => $argument->getValue() !== $value,
        );

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutArguments(): static
    {
        $new = clone $this;

        $new->arguments = [];

        return $new;
    }

    /**
     * @inheritDoc
     *
     * @return Option[]
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
    public function getOption(string $name): array
    {
        return array_filter(
            $this->options,
            static fn (Option $option): bool => $option->getName() === $name,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasOption(string $name): bool
    {
        return $this->getOption($name) !== [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withOptions(Option ...$options): static
    {
        $new = clone $this;

        $new->options = $options;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withAddedOption(Option $option): static
    {
        $new = clone $this;

        $new->options[] = $option;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutOption(string $name): static
    {
        $new = clone $this;

        $new->options = array_filter(
            $this->options,
            static fn (Option $option): bool => $option->getName() !== $name,
        );

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withoutOptions(): static
    {
        $new = clone $this;

        $new->options = [];

        return $new;
    }
}
