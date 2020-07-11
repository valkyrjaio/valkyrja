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

namespace Valkyrja\SMS\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\SMS\Messages\NexmoMessage;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const USERNAME = '';
    public const PASSWORD = '';
    public const MESSAGE  = CKP::NEXMO;
    public const MESSAGES = [
        CKP::NEXMO => NexmoMessage::class,
    ];

    public static array $defaults = [
        CKP::USERNAME => self::USERNAME,
        CKP::PASSWORD => self::PASSWORD,
        CKP::MESSAGE  => self::MESSAGE,
        CKP::MESSAGES => self::MESSAGES,
    ];
}
