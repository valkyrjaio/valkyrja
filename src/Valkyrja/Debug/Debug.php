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

/**
 * Class Debug.
 *
 *
 * @author  Melech Mizrachi
 */
class Debug
{
    /**
     * Whether debug is enabled or not.
     *
     * @var bool
     */
    public static $enabled = false;

    /**
     * Enable debug mode.
     *
     * @param int  $errorReportingLevel [optional]
     * @param bool $displayErrors       [optional]
     *
     * @return void
     */
    public static function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void
    {
        // If debug is already enabled
        if (static::$enabled) {
            // Don't do things twice
            return;
        }

        // Debug is enabled
        static::$enabled = true;

        // The exception handler
        $exceptionHandler = new ExceptionHandler($displayErrors);
        // The error handler
        $errorHandler = new ErrorHandler($displayErrors);

        // Set the error reporting level
        error_reporting($errorReportingLevel);

        // Set the error handler
        set_error_handler(
            [
                $errorHandler,
                'handleError',
            ]
        );

        // Set the exception handler
        set_exception_handler(
            [
                $exceptionHandler,
                'handleException',
            ]
        );

        // Register a shutdown function
        register_shutdown_function(
            [
                $exceptionHandler,
                'handleShutdown',
            ]
        );
    }
}
