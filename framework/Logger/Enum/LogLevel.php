<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Logger\Enum;

use Valkyrja\Enum\Enum;

/**
 * Class LogLevel
 *
 * @package Valkyrja\Logger\Enum
 *
 * @author  Melech Mizrachi
 */
final class LogLevel extends Enum
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
