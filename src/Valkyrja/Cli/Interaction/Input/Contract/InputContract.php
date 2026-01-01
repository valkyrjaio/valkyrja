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

namespace Valkyrja\Cli\Interaction\Input\Contract;

use Valkyrja\Cli\Interaction\Argument\Contract\ArgumentContract;
use Valkyrja\Cli\Interaction\Option\Contract\OptionContract;

/**
 * Interface InputContract.
 */
interface InputContract
{
    /**
     * Get the caller (application name).
     *
     * @return non-empty-string
     */
    public function getCaller(): string;

    /**
     * Create a new Input with the specified caller.
     *
     * @param non-empty-string $caller The caller name
     *
     * @return static
     */
    public function withCaller(string $caller): static;

    /**
     * Get the command name.
     *
     * @return non-empty-string
     */
    public function getCommandName(): string;

    /**
     * Create a new Input with the specified command name.
     *
     * @param non-empty-string $commandName The command name
     *
     * @return static
     */
    public function withCommandName(string $commandName): static;

    /**
     * Get all the arguments.
     *
     * @return ArgumentContract[]
     */
    public function getArguments(): array;

    /**
     * Create a new Input with the specified arguments.
     *
     * @param ArgumentContract ...$arguments The arguments
     *
     * @return static
     */
    public function withArguments(ArgumentContract ...$arguments): static;

    /**
     * Create a new Input with an added argument.
     *
     * @param ArgumentContract $argument The argument to add
     *
     * @return static
     */
    public function withAddedArgument(ArgumentContract $argument): static;

    /**
     * Create a new Input without a specified argument value.
     *
     * @param string $value The argument value to find and remove
     *
     * @return static
     */
    public function withoutArgument(string $value): static;

    /**
     * Create a new Input without any arguments.
     *
     * @return static
     */
    public function withoutArguments(): static;

    /**
     * Get all the options.
     *
     * @return OptionContract[]
     */
    public function getOptions(): array;

    /**
     * Get an option by name.
     *
     * @param string $name The option name
     *
     * @return OptionContract[]
     */
    public function getOption(string $name): array;

    /**
     * Determine if an option exists.
     *
     * @param string $name The option name
     *
     * @return bool
     */
    public function hasOption(string $name): bool;

    /**
     * Create a new Input with the specified options.
     *
     * @param OptionContract ...$options The options
     *
     * @return static
     */
    public function withOptions(OptionContract ...$options): static;

    /**
     * Create a new Input with an added option.
     *
     * @param OptionContract $option The option to add
     *
     * @return static
     */
    public function withAddedOption(OptionContract $option): static;

    /**
     * Create a new Input without a specific option name.
     *
     * @param string $name The option name to remove
     *
     * @return static
     */
    public function withoutOption(string $name): static;

    /**
     * Create a new Input without any options.
     *
     * @return static
     */
    public function withoutOptions(): static;
}
