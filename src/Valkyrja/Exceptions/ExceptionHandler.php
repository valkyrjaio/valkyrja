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

use Throwable;
use Valkyrja\Http\Response;

/**
 * Interface ExceptionHandler.
 *
 * @author Melech Mizrachi
 */
interface ExceptionHandler
{
    /**
     * Enable exception handler.
     *
     * @param int  $errorReportingLevel [optional] The error reporting level
     * @param bool $displayErrors       [optional] Whether to display errors
     *
     * @return void
     */
    public static function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void;

    /**
     * Get a response from a throwable.
     *
     * @param Throwable $exception
     *
     * @return Response
     */
    public function response(Throwable $exception): Response;
}
