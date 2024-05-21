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

namespace Valkyrja\Broadcast\Constants;

use Valkyrja\Broadcast\Adapters\CryptPusherAdapter;
use Valkyrja\Broadcast\Adapters\LogAdapter;
use Valkyrja\Broadcast\Adapters\NullAdapter;
use Valkyrja\Broadcast\Adapters\PusherAdapter;
use Valkyrja\Broadcast\Drivers\Driver;
use Valkyrja\Broadcast\Messages\Message;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT         = CKP::PUSHER;
    public const DEFAULT_MESSAGE = CKP::DEFAULT;
    public const ADAPTER         = PusherAdapter::class;
    public const DRIVER          = Driver::class;
    public const MESSAGE         = Message::class;
    public const BROADCASTERS    = [
        CKP::LOG    => [
            CKP::ADAPTER => LogAdapter::class,
            CKP::DRIVER  => null,
            CKP::LOGGER  => null,
        ],
        CKP::NULL   => [
            CKP::ADAPTER => NullAdapter::class,
            CKP::DRIVER  => null,
        ],
        CKP::PUSHER => [
            CKP::ADAPTER => CryptPusherAdapter::class,
            CKP::DRIVER  => null,
            CKP::KEY     => '',
            CKP::SECRET  => '',
            CKP::ID      => '',
            CKP::CLUSTER => '',
            CKP::USE_TLS => true,
        ],
    ];
    public const MESSAGES        = [
        CKP::DEFAULT => null,
    ];

    /** @var array<string, mixed> */
    public static array $defaults = [
        CKP::DEFAULT         => self::DEFAULT,
        CKP::DEFAULT_MESSAGE => self::DEFAULT_MESSAGE,
        CKP::ADAPTER         => self::ADAPTER,
        CKP::DRIVER          => self::DRIVER,
        CKP::MESSAGE         => self::MESSAGE,
        CKP::BROADCASTERS    => self::BROADCASTERS,
        CKP::MESSAGES        => self::MESSAGES,
    ];
}
