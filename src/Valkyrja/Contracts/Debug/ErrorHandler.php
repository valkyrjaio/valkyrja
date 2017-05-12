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

use Exception;

/**
 * Interface ErrorHandler.
 *
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
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0, array $context = []): void;

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    public function fatalExceptionFromError(array $error): Exception;
}
