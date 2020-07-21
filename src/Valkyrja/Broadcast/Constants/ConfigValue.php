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

use Valkyrja\Broadcast\Adapters\CacheAdapter;
use Valkyrja\Broadcast\Adapters\CryptPusherAdapter;
use Valkyrja\Broadcast\Adapters\LogAdapter;
use Valkyrja\Broadcast\Adapters\NullAdapter;
use Valkyrja\Broadcast\Adapters\PusherAdapter;
use Valkyrja\Broadcast\Messages\Message;
use Valkyrja\Cache\Constants\ConfigValue as CacheConfigValue;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Log\Constants\ConfigValue as LogConfigValue;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const ADAPTER  = CKP::CRYPT;
    public const ADAPTERS = [
        CKP::CACHE  => CacheAdapter::class,
        CKP::NULL   => NullAdapter::class,
        CKP::LOG    => LogAdapter::class,
        CKP::PUSHER => PusherAdapter::class,
        CKP::CRYPT  => CryptPusherAdapter::class,
    ];
    public const MESSAGE  = CKP::DEFAULT;
    public const MESSAGES = [
        CKP::DEFAULT => Message::class,
    ];
    public const DISKS    = [
        CKP::CACHE  => [
            CKP::STORE => CacheConfigValue::STORE,
        ],
        CKP::LOG    => [
            CKP::ADAPTER => LogConfigValue::ADAPTER,
        ],
        CKP::PUSHER => [
            CKP::KEY     => '',
            CKP::SECRET  => '',
            CKP::ID      => '',
            CKP::CLUSTER => '',
            CKP::USE_TLS => true,
        ],
    ];

    public static array $defaults = [
        CKP::ADAPTER  => self::ADAPTER,
        CKP::ADAPTERS => self::ADAPTERS,
        CKP::MESSAGE  => self::MESSAGE,
        CKP::MESSAGES => self::MESSAGES,
        CKP::DISKS    => self::DISKS,
    ];
}
