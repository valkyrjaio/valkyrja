<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Throwable\Handler\Contract;

use const E_ALL;

interface ThrowableHandlerContract
{
    /**
     * Enable exception handler.
     *
     * @param int  $errorReportingLevel [optional] The error reporting level
     * @param bool $displayErrors       [optional] Whether to display errors
     */
    public function enable(int $errorReportingLevel = E_ALL, bool $displayErrors = false): void;
}
