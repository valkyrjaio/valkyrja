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
     * Determine whether a command exists.
     *
     * @param string $command The command
     *
     * @return bool
     */
    public function hasCommand(string $command): bool;

    /**
     * Remove a command.
     *
     * @param string $command The command
     *
     * @return void
     */
    public function removeCommand(string $command): void;

    /**
     * Dispatch a command.
     *
     * @param string $commandName The command name
     * @param array  $arguments   The arguments
     *
     * @return mixed
     */
    public function dispatch(string $commandName, array $arguments = []);

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
     * Get a cacheable representation of the commands.
     *
     * @return array
     */
    public function getCacheable(): array;
}
