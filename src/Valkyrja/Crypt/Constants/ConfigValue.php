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

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const KEY      = 'some_secret_key';
    public const KEY_PATH = null;
    public const ADAPTER  = CKP::SODIUM;
    public const ADAPTERS = [
        CKP::SODIUM => SodiumAdapter::class,
    ];

    public static array $defaults = [
        CKP::KEY      => self::KEY,
        CKP::KEY_PATH => self::KEY_PATH,
        CKP::ADAPTER  => self::ADAPTER,
        CKP::ADAPTERS => self::ADAPTERS,
    ];
}
