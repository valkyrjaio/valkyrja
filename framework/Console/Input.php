<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console;

use Valkyrja\Contracts\Console\Input as InputContract;
use Valkyrja\Contracts\Http\Request;

/**
 * Class Input
 *
 * @package Valkyrja\Console
 *
 * @author  Melech Mizrachi
 */
class Input implements InputContract
{
    /**
     * The request.
     *
     * @var \Valkyrja\Contracts\Http\Request
     */
    protected $request;

    /**
     * The arguments.
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The short options.
     *
     * @var array
     */
    protected $shortOptions = [];

    /**
     * The long options.
     *
     * @var array
     */
    protected $longOptions = [];

    /**
     * Input constructor.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->parseRequestArguments();
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments ?? $this->getRequestArguments();
    }

    /**
     * Get the arguments as a string.
     *
     * @return string
     */
    public function getStringArguments(): string
    {
        return implode(' ', $this->getArguments());
    }

    /**
     * Get the arguments from the request.
     *
     * @return array
     */
    public function getRequestArguments(): array
    {
        $arguments = $this->request->server()->get('argv');

        // strip the application name
        array_shift($arguments);

        return $arguments;
    }

    /**
     * Parse request arguments to split by options and arguments.
     *
     * @return void
     */
    protected function parseRequestArguments(): void
    {
        // Iterate through the request arguments
        foreach ($this->getRequestArguments() as $argument) {
            // Split the string on an equal sign
            $exploded = explode('=', $argument);

            $key = $exploded[0];
            $value = $exploded[1] ?? null;

            // If the key has double dash it is a long option
            if (strpos($key, '--') !== false) {
                // Set it as a long option
                $this->longOptions[$key] = $value;
            }
            // If the key has a single dash it is a short option
            else if (strpos($key, '-') !== false) {
                // Set it as a short option
                $this->shortOptions[$key] = $value;
            }
            // Otherwise it is an argument
            else {
                // Set it as an argument
                $this->arguments[$key] = $value;
            }
        }
    }

    /**
     * Get an argument.
     *
     * @param string $argument The argument
     *
     * @return string
     */
    public function getArgument(string $argument):? string
    {
        return $this->arguments[$argument] ?? null;
    }

    /**
     * Determine if an argument exists.
     *
     * @param string $argument The argument
     *
     * @return bool
     */
    public function hasArgument(string $argument): bool
    {
        return isset($this->arguments[$argument]);
    }

    /**
     * Get a short option.
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getShortOption(string $option):? string
    {
        return $this->shortOptions[$option] ?? null;
    }

    /**
     * Determine if a short option exists.
     *
     * @param string $option The short option
     *
     * @return bool
     */
    public function hasShortOption(string $option): bool
    {
        return isset($this->shortOptions[$option]);
    }

    /**
     * Get a long option.
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getLongOption(string $option):? string
    {
        return $this->longOptions[$option] ?? null;
    }

    /**
     * Determine if a long option exists.
     *
     * @param string $option The option
     *
     * @return bool
     */
    public function hasLongOption(string $option): bool
    {
        return isset($this->longOptions[$option]);
    }

    /**
     * Get an option (short or long).
     *
     * @param string $option The option
     *
     * @return string
     */
    public function getOption(string $option):? string
    {
        return $this->shortOptions[$option] ?? $this->longOptions[$option] ?? null;
    }

    /**
     * Check if an option exists (long or short)
     *
     * @param string $option The option
     *
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return $this->hasShortOption($option) || $this->hasLongOption($option);
    }
}
