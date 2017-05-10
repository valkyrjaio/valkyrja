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

use Throwable;
use Valkyrja\Contracts\Http\Response;

/**
 * Interface ExceptionHandler.
 *
 *
 * @author  Melech Mizrachi
 */
interface ExceptionHandler
{
    /**
     * ExceptionHandler constructor.
     */
    public function __construct();

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
    public function handleException(Throwable $exception): void;

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public function handleShutdown(): void;

    /**
     * Get a response from an exception.
     *
     * @param \Throwable $exception The exception
     *
     * @return \Valkyrja\Contracts\Http\Response
     */
    public function getResponse($exception): Response;

    /**
     * Send response.
     *
     * @param \Throwable $exception
     *
     * @return void
     */
    public function sendResponse($exception): void;

    /**
     * Gets the HTML content associated with the given exception.
     *
     * @param \Throwable $exception A FlattenException instance
     *
     * @return string The content as a string
     */
    public function getContent(Throwable $exception): string;

    /**
     * Gets the stylesheet associated with the given exception.
     *
     * @return string The stylesheet as a string
     */
    public function getStylesheet(): string;

    /**
     * Decorate the html response.
     *
     * @param string $content
     * @param string $css
     *
     * @return string
     */
    public function html(string $content, string $css): string;
}
