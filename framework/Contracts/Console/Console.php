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
use Valkyrja\Contracts\Application;

/**
 * Interface Console
 *
 * @package Valkyrja\Contracts\Console
 *
 * @author  Melech Mizrachi
 */
interface Console
{
    /**
     * The variable regex.
     *
     * @constant string
     */
    public const VARIABLE_REGEX = <<<'REGEX'
\{
    \s* ([a-zA-Z0-9_-]*) \s*
    (?:
        : \s* 
        (
            [
                ^{}]*
                (?:\{(?-1)\}
                [^{}
            ]*)
        *)
    )?
\}
REGEX;

    /**
     * Console constructor.
     *
     * @param \Valkyrja\Contracts\Application $application The application
     */
    public function __construct(Application $application);

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
     * @param \Valkyrja\Contracts\Console\Input $input The input
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
     * @param \Valkyrja\Contracts\Console\Input  $input  The input
     * @param \Valkyrja\Contracts\Console\Output $output The output
     *
     * @return mixed
     *
     * @throws \Valkyrja\Console\Exceptions\CommandNotFound
     */
    public function dispatch(Input $input, Output $output);

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
     * Setup the container.
     *
     * @return void
     */
    public function setup(): void;

    /**
     * Get the named commands list.
     *
     * @return array
     */
    public function getNamedCommands(): array;

    /**
     * Get a cacheable representation of the commands.
     *
     * @return \Valkyrja\Console\Command[]
     */
    public function getCacheable(): array;
}
