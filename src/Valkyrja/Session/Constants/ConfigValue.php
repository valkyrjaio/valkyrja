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

namespace Valkyrja\Session\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const SESSION_ID   = null;
    public const SESSION_NAME = null;

    public static array $defaults = [
        CKP::ID   => self::SESSION_ID,
        CKP::NAME => self::SESSION_NAME,
    ];
}
