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

use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Log\Adapter\PsrAdapter;
use Valkyrja\Log\Driver\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT = CKP::PSR;
    public const ADAPTER = PsrAdapter::class;
    public const DRIVER  = Driver::class;
    public const LOGGERS = [
        CKP::PSR => [
            CKP::ADAPTER   => null,
            CKP::NAME      => 'application-log',
            CKP::FILE_PATH => '',
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::LOGGERS => self::LOGGERS,
    ];
}
