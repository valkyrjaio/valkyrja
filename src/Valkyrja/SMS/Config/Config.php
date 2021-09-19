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

use Valkyrja\Config\Config as Model;
use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NexmoAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Drivers\Driver;
use Valkyrja\SMS\Messages\Message;

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
     * The default messenger.
     *
     * @var string
     */
    public string $default = CKP::NEXMO;

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
    public string $adapter = NexmoAdapter::class;

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
     * The messages.
     *
     * @var array[]
     */
    public array $messages = [
        CKP::DEFAULT => [
            CKP::MESSAGE   => null,
            CKP::FROM_NAME => 'Example',
        ],
    ];
}
