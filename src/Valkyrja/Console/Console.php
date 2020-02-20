<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Console\Exceptions\CommandNotFound;
use Valkyrja\Support\Cacheable;
use Valkyrja\Support\Providers\ProvidersAware;

/**
 * Interface Console.
 *
 * @author Melech Mizrachi
 */
interface Console extends Cacheable, ProvidersAware
{
    /**
     * Add a new command.
     *
     * @param Command $command The command
     *
     * @return void
     */
    public function addCommand(Command $command): void;

    /**
     * Get a command by name.
     *
     * @param string $name The command name
     *
     * @return Command
     */
    public function command(string $name): ?Command;

    /**
     * Determine whether a command exists.
     *
     * @param string $name The command
     *
     * @return bool
     */
    public function hasCommand(string $name): bool;

    /**
     * Remove a command.
     *
     * @param string $name The command
     *
     * @return void
     */
    public function removeCommand(string $name): void;

    /**
     * Get a command from an input.
     *
     * @param Input $input The input
     *
     * @throws CommandNotFound
     *
     * @return Command
     */
    public function inputCommand(Input $input): Command;

    /**
     * Match a command.
     *
     * @param string $path The command name
     *
     * @throws CommandNotFound
     *
     * @return Command
     */
    public function matchCommand(string $path): Command;

    /**
     * Dispatch a command.
     *
     * @param Input  $input  The input
     * @param Output $output The output
     *
     * @throws CommandNotFound
     *
     * @return mixed
     */
    public function dispatch(Input $input, Output $output);

    /**
     * Dispatch a command.
     *
     * @param Command $command The command
     *
     * @return mixed
     */
    public function dispatchCommand(Command $command);

    /**
     * Get all commands.
     *
     * @return Command[]
     */
    public function all(): array;

    /**
     * Set the commands.
     *
     * @param Command ...$commands The commands
     *
     * @return void
     */
    public function set(Command ...$commands): void;

    /**
     * Get the named commands list.
     *
     * @return array
     */
    public function getNamedCommands(): array;
}
