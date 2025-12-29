<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Throwable\Handler\Contract;

use Throwable;

use const E_ALL;

/**
 * Interface ThrowableHandler.
 *
 * @author Melech Mizrachi
 */
interface ThrowableHandler
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
     * Get trace code for a throwable/exception.
     *
     * @param Throwable $throwable The exception/throwable
     *
     * @return string
     */
    public static function getTraceCode(Throwable $throwable): string;
}
