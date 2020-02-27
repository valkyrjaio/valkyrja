<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

/**
 * Interface Commander.
 *
 * @author Melech Mizrachi
 */
interface Commander
{
    /**
     * Get the command.
     *
     * @return string
     */
    public static function getCommand(): string;

    /**
     * Get the command path.
     *
     * @return string
     */
    public static function getPath(): string;

    /**
     * Get the short description.
     *
     * @return string
     */
    public static function getShortDescription(): string;

    /**
     * Get the description.
     *
     * @return string
     */
    public static function getDescription(): string;

    /**
     * Help docs for this command.
     *
     * @return int
     */
    public function help(): int;

    /**
     * The run handler.
     *
     * @return int
     */
    public function run(): int;

    /**
     * Get the command version.
     *
     * @return int
     */
    public function version(): int;
}
