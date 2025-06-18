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
use Valkyrja\Broadcast\Constant\EnvName;

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
        ConfigName::ADAPTER_CLASS => EnvName::PUSHER_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::PUSHER_DRIVER_CLASS,
        ConfigName::KEY           => EnvName::PUSHER_KEY,
        ConfigName::SECRET        => EnvName::PUSHER_SECRET,
        ConfigName::ID            => EnvName::PUSHER_ID,
        ConfigName::CLUSTER       => EnvName::PUSHER_CLUSTER,
        ConfigName::USE_TLS       => EnvName::PUSHER_USE_TLS,
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
