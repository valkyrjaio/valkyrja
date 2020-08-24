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

namespace Valkyrja\Client\Constants;

use Valkyrja\Client\Adapters\GuzzleAdapter;
use Valkyrja\Client\Adapters\LogAdapter;
use Valkyrja\Client\Adapters\NullAdapter;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::DEFAULT;
    public const ADAPTERS = [
        CKP::GUZZLE => GuzzleAdapter::class,
        CKP::NULL   => NullAdapter::class,
        CKP::LOG    => LogAdapter::class,
    ];
    public const DRIVERS  = [
        CKP::DEFAULT => GuzzleAdapter::class,
    ];
    public const CLIENTS  = [
        CKP::DEFAULT => [
            CKP::ADAPTER => CKP::GUZZLE,
            CKP::DRIVER  => CKP::DEFAULT,
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::DRIVERS  => self::DRIVERS,
        CKP::CLIENTS  => self::CLIENTS,
    ];
}
