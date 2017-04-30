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
}
