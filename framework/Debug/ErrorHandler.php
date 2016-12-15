<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Debug;

use ErrorException;
use Exception;

use Valkyrja\Contracts\Debug\ExceptionHandler as ErrorHandlerContract;

/**
 * Class ErrorHandler
 *
 * @package Valkyrja\Debug
 *
 * @author Melech Mizrachi
 */
class ErrorHandler implements ErrorHandlerContract
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
    public function handleError(int $level, string $message, string $file = '', int $line = 0, array $context = []) : void
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    protected function fatalExceptionFromError(array $error) : Exception
    {
        return new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
    }
}
