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

namespace Valkyrja\Console\Input\Contract;

use Valkyrja\Console\Input\Option;

/**
 * Interface Input.
 *
 * @author Melech Mizrachi
 */
interface Input
{
    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): array;

    /**
     * Get the short options.
     *
     * @return array
     */
    public function getShortOptions(): array;

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getLongOptions(): array;

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getOptions(): array;

    /**
     * Get the arguments as a string.
     *
     * @return string
     */
    public function getStringArguments(): string;

    /**
     * Get the input arguments.
     *
     * @return array
     */
    public function getInputArguments(): array;

    /**
     * Get an argument.
     *
     * @param string $argument The argument
     *
     * @return string|null
     */
    public function getArgument(string $argument): string|null;

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
     * @return string|null
     */
    public function getShortOption(string $option): string|null;

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
     * @return string|null
     */
    public function getLongOption(string $option): string|null;

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
     * @return string|null
     */
    public function getOption(string $option): string|null;

    /**
     * Check if an option exists (long or short).
     *
     * @param string $option The option
     *
     * @return bool
     */
    public function hasOption(string $option): bool;

    /**
     * Get the global options.
     *
     * @return Option[]
     */
    public function getGlobalOptions(): array;

    /**
     * Get the global options as a flat array.
     *
     * @return string[]
     */
    public function getGlobalOptionsFlat(): array;
}
