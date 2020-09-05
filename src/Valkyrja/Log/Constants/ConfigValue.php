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

namespace Valkyrja\Log\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Log\Adapters\NullAdapter;
use Valkyrja\Log\Adapters\PsrAdapter;
use Valkyrja\Log\Drivers\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::PSR;
    public const ADAPTERS = [
        CKP::NULL => NullAdapter::class,
        CKP::PSR  => PsrAdapter::class,
    ];
    public const DRIVERS  = [
        CKP::DEFAULT => Driver::class,
    ];
    public const LOGGERS  = [
        CKP::PSR => [
            CKP::ADAPTER   => CKP::PSR,
            CKP::DRIVER    => CKP::DEFAULT,
            CKP::NAME      => 'application-log',
            CKP::FILE_PATH => '',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::DRIVERS  => self::ADAPTERS,
        CKP::LOGGERS  => self::ADAPTERS,
    ];
}
