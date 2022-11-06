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
use Valkyrja\Broadcast\Adapters\PusherAdapter;
use Valkyrja\Broadcast\Drivers\Driver;
use Valkyrja\Broadcast\Messages\Message;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Support\Manager\Config\MessageConfig as Model;

/**
 * Class Config.
 *
 * @author Melech Mizrachi
 */
class Config extends Model
{
    /**
     * @inheritDoc
     */
    protected static array $envKeys = [
        CKP::DEFAULT         => EnvKey::BROADCAST_DEFAULT,
        CKP::DEFAULT_MESSAGE => EnvKey::BROADCAST_DEFAULT_MESSAGE,
        CKP::ADAPTER         => EnvKey::BROADCAST_ADAPTER,
        CKP::DRIVER          => EnvKey::BROADCAST_DRIVER,
        CKP::MESSAGE         => EnvKey::BROADCAST_MESSAGE,
        CKP::BROADCASTERS    => EnvKey::BROADCAST_BROADCASTERS,
        CKP::MESSAGES        => EnvKey::BROADCAST_MESSAGES,
    ];

    /**
     * @inheritDoc
     */
    public string $default = CKP::PUSHER;

    /**
     * @inheritDoc
     */
    public string $adapter = PusherAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * @inheritDoc
     */
    public string $message = Message::class;

    /**
     * The adapters.
     *
     * @var array
     */
    public array $broadcasters = [
        CKP::LOG    => [
            CKP::ADAPTER => LogAdapter::class,
            CKP::DRIVER  => null,
            CKP::LOGGER  => null,
        ],
        CKP::NULL   => [
            CKP::ADAPTER => NullAdapter::class,
        ],
        CKP::PUSHER => [
            CKP::DRIVER  => null,
            CKP::ADAPTER => CryptPusherAdapter::class,
            CKP::KEY     => '',
            CKP::SECRET  => '',
            CKP::ID      => '',
            CKP::CLUSTER => '',
            CKP::USE_TLS => true,
        ],
    ];

    /**
     * @inheritDoc
     */
    public array $messages = [
        CKP::DEFAULT => null,
    ];
}
