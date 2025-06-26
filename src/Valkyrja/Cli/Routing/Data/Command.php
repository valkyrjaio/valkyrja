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

use Valkyrja\Cli\Interaction\Message\Contract\Message;
use Valkyrja\Cli\Routing\Data\Contract\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\Contract\Command as Contract;
use Valkyrja\Cli\Routing\Data\Contract\OptionParameter;
use Valkyrja\Cli\Routing\Data\Contract\Parameter;
use Valkyrja\Dispatcher\Data\Contract\MethodDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch as DefaultDispatch;

/**
 * Class Command.
 *
 * @author Melech Mizrachi
 */
class Command implements Contract
{
    /** @var ArgumentParameter[] */
    protected array $arguments = [];

    /** @var OptionParameter[] */
    protected array $options = [];

    /**
     * @param non-empty-string $name        The name
     * @param non-empty-string $description The description
     * @param Message          $helpText    The help text
     * @param Parameter[]      $parameters  The parameters
     */
    public function __construct(
        protected string $name,
        protected string $description,
        protected Message $helpText,
        protected MethodDispatch $dispatch = new DefaultDispatch(self::class, '__construct'),
        array $parameters = [],
    ) {
        foreach ($parameters as $parameter) {
            if ($parameter instanceof ArgumentParameter) {
                $this->arguments[] = $parameter;
            } elseif ($parameter instanceof OptionParameter) {
                $this->options[] = $parameter;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function withDescription(string $description): static
    {
        $new = clone $this;

        $new->description = $description;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getHelpText(): Message
    {
        return $this->helpText;
    }

    /**
     * @inheritDoc
     */
    public function withHelpText(Message $helpText): static
    {
        $new = clone $this;

        $new->helpText = $helpText;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function hasArguments(): bool
    {
        return $this->arguments !== [];
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function getArgument(string $name): ArgumentParameter|null
    {
        $argument = array_filter($this->arguments, static fn (ArgumentParameter $argument) => $argument->getName() === $name);

        return $argument[0] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function withArguments(ArgumentParameter ...$arguments): static
    {
        $new = clone $this;

        $new->arguments = $arguments;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedArguments(ArgumentParameter ...$arguments): static
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
    public function hasOptions(): bool
    {
        return $this->options !== [];
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name): OptionParameter|null
    {
        $options = array_filter($this->options, static fn (OptionParameter $option) => $option->getName() === $name);

        return $options[0] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function withOptions(OptionParameter ...$options): static
    {
        $new = clone $this;

        $new->options = $options;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedOptions(OptionParameter ...$options): static
    {
        $new = clone $this;

        $new->options = [
            ...$this->options,
            ...$options,
        ];

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getDispatch(): MethodDispatch
    {
        return $this->dispatch;
    }

    /**
     * @inheritDoc
     */
    public function withDispatch(MethodDispatch $dispatch): static
    {
        $new = clone $this;

        $new->dispatch = $dispatch;

        return $new;
    }
}
