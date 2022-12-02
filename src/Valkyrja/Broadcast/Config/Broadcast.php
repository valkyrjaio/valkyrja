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

namespace Valkyrja\Broadcast\Config;

use Valkyrja\Broadcast\Adapters\CryptPusherAdapter;
use Valkyrja\Broadcast\Adapters\LogAdapter;
use Valkyrja\Broadcast\Adapters\NullAdapter;
use Valkyrja\Broadcast\Config\Config as Model;
use Valkyrja\Broadcast\Constants\ConfigValue;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

use function Valkyrja\env;

/**
 * Class Broadcast.
 */
class Broadcast extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->broadcasters = [
            CKP::LOG    => [
                CKP::ADAPTER => env(EnvKey::BROADCAST_LOG_ADAPTER, LogAdapter::class),
                CKP::DRIVER  => env(EnvKey::BROADCAST_LOG_DRIVER),
                // null will use default adapter as set in log config
                CKP::LOGGER  => env(EnvKey::BROADCAST_LOG_LOGGER),
            ],
            CKP::NULL   => [
                CKP::ADAPTER => env(EnvKey::BROADCAST_NULL_ADAPTER, NullAdapter::class),
                CKP::DRIVER  => env(EnvKey::BROADCAST_NULL_DRIVER),
            ],
            CKP::PUSHER => [
                CKP::ADAPTER => env(EnvKey::BROADCAST_PUSHER_DRIVER, CryptPusherAdapter::class),
                CKP::DRIVER  => env(EnvKey::BROADCAST_PUSHER_DRIVER),
                CKP::KEY     => env(EnvKey::BROADCAST_PUSHER_KEY, ''),
                CKP::SECRET  => env(EnvKey::BROADCAST_PUSHER_SECRET, ''),
                CKP::ID      => env(EnvKey::BROADCAST_PUSHER_ID, ''),
                CKP::CLUSTER => env(EnvKey::BROADCAST_PUSHER_CLUSTER, 'us1'),
                CKP::USE_TLS => env(EnvKey::BROADCAST_PUSHER_USE_TLS, true),
            ],
        ];
    }
}
