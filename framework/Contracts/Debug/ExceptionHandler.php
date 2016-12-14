<?php

namespace Valkyrja\Contracts\Debug;

use Throwable;

interface ExceptionHandler
{
    /**
     * ExceptionHandler constructor.
     */
    public function __construct();

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

    /**
     * Handle an uncaught exception from the application.
     *
     * Note: Most exceptions can be handled via the try / catch block in
     * the HTTP and Console kernels. But, fatal error exceptions must
     * be handled differently since they are not normal exceptions.
     *
     * @param \Throwable $exception The exception that was captured
     *
     * @return void
     */
    public function handleException(Throwable $exception) : void;

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown() : void;

    /**
     * Send response.
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function sendResponse($exception) : void;

    /**
     * Gets the HTML content associated with the given exception.
     *
     * @param \Throwable $exception A FlattenException instance
     *
     * @return string The content as a string
     */
    public function getContent(Throwable $exception) : string;

    /**
     * Gets the stylesheet associated with the given exception.
     *
     * @return string The stylesheet as a string
     */
    public function getStylesheet() : string;

    /**
     * Decorate the html response.
     *
     * @param string $content
     * @param string $css
     *
     * @return string
     */
    public function html(string $content, string $css) : string;
}
