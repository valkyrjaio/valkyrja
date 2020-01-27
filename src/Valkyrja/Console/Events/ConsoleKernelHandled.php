<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Console\Events;

use Valkyrja\Console\Input\Input;
use Valkyrja\Model\Model;

/**
 * Class ConsoleKernelHandled.
 */
class ConsoleKernelHandled extends Model
{
    /**
     * The input request.
     *
     * @var Input
     */
    protected Input $input;

    /**
     * The exit code.
     *
     * @var int
     */
    protected int $exitCode;

    /**
     * ConsoleKernelHandled constructor.
     *
     * @param Input $input
     * @param int   $exitCode
     */
    public function __construct(Input $input, int $exitCode)
    {
        $this->input    = $input;
        $this->exitCode = $exitCode;
    }

    /**
     * Get the input request.
     *
     * @return Input
     */
    public function getInput(): Input
    {
        return $this->input;
    }

    /**
     * Set the input request.
     *
     * @param Input $input
     *
     * @return void
     */
    public function setInput(Input $input): void
    {
        $this->input = $input;
    }

    /**
     * Get the exit code.
     *
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * Set the exit code.
     *
     * @param int $exitCode
     *
     * @return void
     */
    public function setExitCode(int $exitCode): void
    {
        $this->exitCode = $exitCode;
    }
}
