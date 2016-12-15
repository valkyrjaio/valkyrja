<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Debug;

/**
 * Interface ErrorHandler
 *
 * @package Valkyrja\Contracts\Debug
 *
 * @author  Melech Mizrachi
 */
interface ErrorHandler
{
    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param int    $level   The error level
     * @param string $message The error message
     * @param string $file    [optional] The file within which the error occurred
     * @param int    $line    [optional] The line which threw the error
     * @param array  $context [optional] The context for the exception
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0, array $context = []) : void;
}
