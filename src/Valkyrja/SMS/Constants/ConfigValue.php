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
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NexmoAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Drivers\Driver;
use Valkyrja\SMS\Messages\Message;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT          = CKP::NEXMO;
    public const ADAPTERS         = [
        CKP::LOG   => LogAdapter::class,
        CKP::NEXMO => NexmoAdapter::class,
        CKP::NULL  => NullAdapter::class,
    ];
    public const DRIVERS          = [
        CKP::DEFAULT => Driver::class,
    ];
    public const MESSENGERS       = [
        CKP::LOG   => [
            CKP::ADAPTER => CKP::LOG,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::LOGGER  => null,
        ],
        CKP::NEXMO => [
            CKP::ADAPTER  => CKP::NULL,
            CKP::DRIVER   => CKP::DEFAULT,
            CKP::USERNAME => '',
            CKP::PASSWORD => '',
        ],
        CKP::NULL  => [
            CKP::ADAPTER => CKP::NULL,
            CKP::DRIVER  => CKP::DEFAULT,
        ],
    ];
    public const DEFAULT_MESSAGE  = CKP::DEFAULT;
    public const MESSAGE_ADAPTERS = [
        CKP::DEFAULT => Message::class,
    ];
    public const MESSAGES         = [
        CKP::DEFAULT => [
            CKP::ADAPTER   => CKP::DEFAULT,
            CKP::FROM_NAME => 'Example',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT          => self::DEFAULT,
        CKP::ADAPTERS         => self::ADAPTERS,
        CKP::DRIVERS          => self::DRIVERS,
        CKP::MESSENGERS       => self::MESSENGERS,
        CKP::DEFAULT_MESSAGE  => self::DEFAULT_MESSAGE,
        CKP::MESSAGE_ADAPTERS => self::MESSAGE_ADAPTERS,
        CKP::MESSAGES         => self::MESSAGES,
    ];
}
