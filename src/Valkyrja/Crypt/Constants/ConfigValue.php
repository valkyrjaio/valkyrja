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

namespace Valkyrja\Crypt\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Crypt\Adapters\SodiumAdapter;
use Valkyrja\Crypt\Drivers\Driver;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT  = CKP::DEFAULT;
    public const ADAPTERS = [
        CKP::SODIUM => SodiumAdapter::class,
    ];
    public const DRIVERS  = [
        CKP::DEFAULT => Driver::class,
    ];
    public const CRYPTS   = [
        CKP::DEFAULT => [
            CKP::ADAPTER  => CKP::SODIUM,
            CKP::DRIVER   => CKP::DEFAULT,
            CKP::KEY      => 'some_secret_key',
            CKP::KEY_PATH => null,
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT  => self::DEFAULT,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::DRIVERS  => self::DRIVERS,
        CKP::CRYPTS   => self::CRYPTS,
    ];
}
