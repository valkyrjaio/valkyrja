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

namespace Valkyrja\Log\Enums;

use Valkyrja\Support\Enum\Enum;

/**
 * Enum LogLevel.
 *
 * @author Melech Mizrachi
 *
 * @method static LogLevel DEBUG()
 * @method static LogLevel INFO()
 * @method static LogLevel NOTICE()
 * @method static LogLevel WARNING()
 * @method static LogLevel ERROR()
 * @method static LogLevel CRITICAL()
 * @method static LogLevel ALERT()
 * @method static LogLevel EMERGENCY()
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

    protected static ?array $VALUES = [
        self::DEBUG     => self::DEBUG,
        self::INFO      => self::INFO,
        self::NOTICE    => self::NOTICE,
        self::WARNING   => self::WARNING,
        self::ERROR     => self::ERROR,
        self::CRITICAL  => self::CRITICAL,
        self::ALERT     => self::ALERT,
        self::EMERGENCY => self::EMERGENCY,
    ];
}
