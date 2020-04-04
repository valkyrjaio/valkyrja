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

namespace Valkyrja\Crypt\Enums;

use Valkyrja\Config\Enums\ConfigKeyPart as CKP;

/**
 * Enum ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const KEY      = 'some_secret_key';
    public const KEY_PATH = null;

    public static array $defaults = [
        CKP::KEY      => self::KEY,
        CKP::KEY_PATH => self::KEY_PATH,
    ];
}
