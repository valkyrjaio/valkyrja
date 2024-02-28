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

namespace Valkyrja\Sms\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Sms\Adapters\LogAdapter;
use Valkyrja\Sms\Adapters\VonageAdapter;
use Valkyrja\Sms\Adapters\NullAdapter;
use Valkyrja\Sms\Drivers\Driver;
use Valkyrja\Sms\Messages\Message;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT         = CKP::NEXMO;
    public const DEFAULT_MESSAGE = CKP::DEFAULT;
    public const ADAPTER         = VonageAdapter::class;
    public const DRIVER          = Driver::class;
    public const MESSAGE         = Message::class;
    public const MESSENGERS      = [
        CKP::NEXMO => [
            CKP::ADAPTER  => null,
            CKP::DRIVER   => null,
            CKP::USERNAME => '',
            CKP::PASSWORD => '',
        ],
        CKP::LOG   => [
            CKP::ADAPTER => LogAdapter::class,
            CKP::DRIVER  => null,
            CKP::LOGGER  => null,
        ],
        CKP::NULL  => [
            CKP::ADAPTER => NullAdapter::class,
            CKP::DRIVER  => null,
        ],
    ];
    public const MESSAGES        = [
        CKP::DEFAULT => [
            CKP::MESSAGE   => null,
            CKP::FROM_NAME => 'Example',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT         => self::DEFAULT,
        CKP::DEFAULT_MESSAGE => self::DEFAULT_MESSAGE,
        CKP::ADAPTER         => self::ADAPTER,
        CKP::DRIVER          => self::DRIVER,
        CKP::MESSAGE         => self::MESSAGE,
        CKP::MESSENGERS      => self::MESSENGERS,
        CKP::MESSAGES        => self::MESSAGES,
    ];
}
