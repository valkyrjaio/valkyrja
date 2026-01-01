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

namespace Valkyrja\Log\Enum;

/**
 * Enum LogLevel.
 */
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
