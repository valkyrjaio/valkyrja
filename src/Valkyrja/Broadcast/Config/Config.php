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
use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;

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
     * The default adapter.
     *
     * @var string
     */
    public string $default = CKP::PUSHER;

    /**
     * The default message.
     *
     * @var string
     */
    public string $defaultMessage = CKP::DEFAULT;

    /**
     * The default adapter.
     *
     * @var string
     */
    public string $adapter = PusherAdapter::class;

    /**
     * The default driver.
     *
     * @var string
     */
    public string $driver = Driver::class;

    /**
     * The default message class.
     *
     * @var string
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
     * The messages.
     *
     * @var array
     */
    public array $messages = [
        CKP::DEFAULT => null,
    ];
}
