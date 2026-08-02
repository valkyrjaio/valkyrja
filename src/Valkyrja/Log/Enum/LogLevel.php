<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Log\Enum;

enum LogLevel: string
{
    case DEBUG     = 'debug';
    case INFO      = 'info';
    case NOTICE    = 'notice';
    case WARNING   = 'warning';
    case ERROR     = 'error';
    case CRITICAL  = 'critical';
    case ALERT     = 'alert';
    case EMERGENCY = 'emergency';
}
