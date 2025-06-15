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

use Valkyrja\Broadcast\Adapter\CryptPusherAdapter;
use Valkyrja\Broadcast\Constant\ConfigName;

/**
 * Class PusherConfiguration.
 *
 * @author Melech Mizrachi
 */
class PusherConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'BROADCAST_PUSHER_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'BROADCAST_PUSHER_DRIVER_CLASS',
        'key'                     => 'BROADCAST_PUSHER_KEY',
        'secret'                  => 'BROADCAST_PUSHER_SECRET',
        'id'                      => 'BROADCAST_PUSHER_ID',
        'cluster'                 => 'BROADCAST_PUSHER_CLUSTER',
        'useTls'                  => 'BROADCAST_PUSHER_USE_TLS',
    ];

    public function __construct(
        public string $key = '',
        public string $secret = '',
        public string $id = '',
        public string $cluster = 'us1',
        public bool $useTls = true
    ) {
        parent::__construct(
            adapterClass: CryptPusherAdapter::class,
        );
    }
}
