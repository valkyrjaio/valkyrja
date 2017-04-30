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
     * The arguments passed in.
     *
     * @var array
     */
    protected $arguments;

    /**
     * Input constructor.
     *
     * @param \Valkyrja\Contracts\Http\Request $request The request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the arguments.
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments ?? $this->arguments = $this->getRequestArguments();
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
}
