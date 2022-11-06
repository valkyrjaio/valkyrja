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

namespace Valkyrja\SMS\Config;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NexmoAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Drivers\Driver;
use Valkyrja\SMS\Messages\Message;
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
        CKP::DEFAULT         => EnvKey::SMS_DEFAULT,
        CKP::DEFAULT_MESSAGE => EnvKey::SMS_DEFAULT_MESSAGE,
        CKP::ADAPTER         => EnvKey::SMS_ADAPTER,
        CKP::DRIVER          => EnvKey::SMS_DRIVER,
        CKP::MESSAGE         => EnvKey::SMS_MESSAGE,
        CKP::MESSENGERS      => EnvKey::SMS_MESSENGERS,
        CKP::MESSAGES        => EnvKey::SMS_MESSAGES,
    ];

    /**
     * @inheritDoc
     */
    public string $default = CKP::NEXMO;

    /**
     * @inheritDoc
     */
    public string $adapter = NexmoAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * @inheritDoc
     */
    public string $message = Message::class;

    /**
     * The messengers.
     *
     * @var array[]
     */
    public array $messengers = [
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

    /**
     * @inheritDoc
     */
    public array $messages = [
        CKP::DEFAULT => [
            CKP::MESSAGE   => null,
            CKP::FROM_NAME => 'Example',
        ],
    ];
}
