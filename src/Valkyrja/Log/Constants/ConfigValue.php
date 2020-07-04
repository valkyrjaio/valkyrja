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

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const LOG_NAME      = 'application-log';
    public const LOG_FILE_PATH = '';

    public static array $defaults = [
        CKP::NAME      => self::LOG_NAME,
        CKP::FILE_PATH => self::LOG_FILE_PATH,
    ];
}
