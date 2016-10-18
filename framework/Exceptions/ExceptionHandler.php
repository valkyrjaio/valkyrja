<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Exceptions;

use Valkyrja\Contracts\Exceptions\HttpException;

/**
 * Class ExceptionHandler
 *
 * @package Valkyrja\Exceptions
 *
 * @author  Melech Mizrachi
 */
trait ExceptionHandler
{
    /**
     * Bootstrap error, exception, and shutdown handler.
     *
     * @return void
     */
    protected function bootstrapHandler()
    {
        error_reporting(-1);

        set_error_handler(
            [
                $this,
                'handleError',
            ]
        );

        set_exception_handler(
            [
                $this,
                'handleException',
            ]
        );

        register_shutdown_function(
            [
                $this,
                'handleShutdown',
            ]
        );

        if (!$this->debug()) {
            ini_set('display_errors', 'Off');
        }
    }

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
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $e The exception that was captured
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function handleException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new \Exception($e);
        }

        $data = [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTrace(),
        ];
        $view = 'errors/500';
        $headers = [];
        $code = 500;

        if ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $headers = $e->getHeaders();
            $view = $e->getView()
                ?: 'errors/' . $code;
        }

        // Return a new sent response
        return $this->response()
                    ->view($view, $data, $code, $headers)
                    ->send();
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last())
            && in_array(
                $error['type'],
                [
                    E_ERROR,
                    E_CORE_ERROR,
                    E_COMPILE_ERROR,
                    E_PARSE,
                ]
            )
        ) {
            $this->handleException($this->fatalExceptionFromError($error));
        }
    }

    /**
     * Throw an http exception.
     *
     * @param int        $statusCode The status code to use
     * @param string     $message    [optional] The Exception message to throw
     * @param \Exception $previous   [optional] The previous exception used for the exception chaining
     * @param array      $headers    [optional] The headers to send
     * @param string     $view       [optional] The view template name to use
     * @param int        $code       [optional] The Exception code
     *
     * @throws HttpException
     */
    public function httpException(
        $statusCode,
        $message = null,
        \Exception $previous = null,
        array $headers = [],
        $view = null,
        $code = 0
    ) {
        throw $this->container(
            HttpException::class,
            [
                $statusCode,
                $message,
                $previous,
                $headers,
                $view,
                $code,
            ]
        );
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array $error The error array to use
     *
     * @return \Exception
     */
    protected function fatalExceptionFromError(array $error)
    {
        return new \ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
    }
}
