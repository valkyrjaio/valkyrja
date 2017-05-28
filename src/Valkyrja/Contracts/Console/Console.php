<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Console;

use Valkyrja\Console\Command;
use Valkyrja\Contracts\Cache\Cacheable;
use Valkyrja\Contracts\Console\Input\Input;
use Valkyrja\Contracts\Console\Output\Output;

/**
 * Interface Console.
 *
 * @author Melech Mizrachi
 */
interface Console extends Cacheable
{
    /**
     * Add a new command.
     *
     * @param \Valkyrja\Console\Command $command The command
     *
     * @return void
     */
    public function addCommand(Command $command): void;

    /**
     * Get a command by name.
     *
     * @param string $name The command name
     *
     * @return \Valkyrja\Console\Command
     */
    public function command(string $name):? Command;

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
     * @param \Valkyrja\Contracts\Console\Input\Input $input The input
     *
     * @return null|\Valkyrja\Console\Command
     */
    public function inputCommand(Input $input):? Command;

    /**
     * Match a command.
     *
     * @param string $path The command name
     *
     * @return \Valkyrja\Console\Command
     */
    public function matchCommand(string $path):? Command;

    /**
     * Dispatch a command.
     *
     * @param \Valkyrja\Contracts\Console\Input\Input   $input  The input
     * @param \Valkyrja\Contracts\Console\Output\Output $output The output
     *
     * @throws \Valkyrja\Console\Exceptions\CommandNotFound
     *
     * @return mixed
     */
    public function dispatch(Input $input, Output $output);

    /**
     * Dispatch a command.
     *
     * @param \Valkyrja\Console\Command $command The command
     *
     * @return mixed
     */
    public function dispatchCommand(Command $command);

    /**
     * Get all commands.
     *
     * @return \Valkyrja\Console\Command[]
     */
    public function all(): array;

    /**
     * Set the commands.
     *
     * @param \Valkyrja\Console\Command[] $commands The commands
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
