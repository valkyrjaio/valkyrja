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

use Valkyrja\Contracts\Http\Request;

/**
 * Interface Input
 *
 * @package Valkyrja\Contracts\Console
 *
 * @author  Melech Mizrachi
 */
interface Input
{
    /**
     * Input constructor.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     */
    public function __construct(Request $request);

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * Get the arguments as a string.
     *
     * @return string
     */
    public function getStringArguments(): string;

    /**
     * Get the arguments from the request.
     *
     * @return array
     */
    public function getRequestArguments(): array;

    /**
     * Get an argument.
     *
     * @param string $argument The argument
     *
     * @return string
     */
    public function getArgument(string $argument):? string;

    /**
     * Determine if an argument exists.
     *
     * @param string $argument The argument
     *
     * @return bool
     */
    public function hasArgument(string $argument): bool;

    /**
     * Get a short option.
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getShortOption(string $option):? string;

    /**
     * Determine if a short option exists.
     *
     * @param string $option The short option
     *
     * @return bool
     */
    public function hasShortOption(string $option): bool;

    /**
     * Get a long option.
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getLongOption(string $option):? string;

    /**
     * Determine if a long option exists.
     *
     * @param string $option The option
     *
     * @return bool
     */
    public function hasLongOption(string $option): bool;

    /**
     * Get an option (short or long).
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getOption(string $option):? string;

    /**
     * Check if an option exists (long or short)
     *
     * @param string $option The option
     *
     * @return bool
     */
    public function hasOption(string $option): bool;
}