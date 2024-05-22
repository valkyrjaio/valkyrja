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

namespace Valkyrja\Log\Constant;

/**
 * Constant LogLevel.
 *
 * @author Melech Mizrachi
 */
final class LogLevel
{
    public const DEBUG     = 'debug';
    public const INFO      = 'info';
    public const NOTICE    = 'notice';
    public const WARNING   = 'warning';
    public const ERROR     = 'error';
    public const CRITICAL  = 'critical';
    public const ALERT     = 'alert';
    public const EMERGENCY = 'emergency';
}
