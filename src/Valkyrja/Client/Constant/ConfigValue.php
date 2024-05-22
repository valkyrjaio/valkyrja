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

namespace Valkyrja\Client\Constant;

use Valkyrja\Client\Adapter\GuzzleAdapter;
use Valkyrja\Client\Driver\Driver;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT = CKP::GUZZLE;
    public const ADAPTER = GuzzleAdapter::class;
    public const DRIVER  = Driver::class;
    public const CLIENTS = [
        CKP::GUZZLE => [
            CKP::ADAPTER => null,
            CKP::DRIVER  => null,
            CKP::OPTIONS => [],
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::CLIENTS => self::CLIENTS,
    ];
}
