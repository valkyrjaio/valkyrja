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
    public const DEFAULT = CKP::SODIUM;
    public const ADAPTER = SodiumAdapter::class;
    public const DRIVER  = Driver::class;
    public const CRYPTS  = [
        CKP::SODIUM => [
            CKP::ADAPTER  => null,
            CKP::DRIVER   => null,
            CKP::KEY      => 'some_secret_key',
            CKP::KEY_PATH => null,
        ],
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT => self::DEFAULT,
        CKP::ADAPTER => self::ADAPTER,
        CKP::DRIVER  => self::DRIVER,
        CKP::CRYPTS  => self::CRYPTS,
    ];
}
