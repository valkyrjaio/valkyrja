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

namespace Valkyrja\Mail\Config;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\Mail\Adapters\LogAdapter;
use Valkyrja\Mail\Adapters\MailgunAdapter;
use Valkyrja\Mail\Adapters\NullAdapter;
use Valkyrja\Mail\Adapters\PHPMailerAdapter;
use Valkyrja\Mail\Drivers\Driver;
use Valkyrja\Mail\Messages\Message;
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
        CKP::DEFAULT         => EnvKey::MAIL_DEFAULT,
        CKP::DEFAULT_MESSAGE => EnvKey::MAIL_DEFAULT_MESSAGE,
        CKP::ADAPTER         => EnvKey::MAIL_ADAPTER,
        CKP::DRIVER          => EnvKey::MAIL_DRIVER,
        CKP::MESSAGE         => EnvKey::MAIL_MESSAGE,
        CKP::MAILERS         => EnvKey::MAIL_MAILERS,
        CKP::MESSAGES        => EnvKey::MAIL_MESSAGES,
    ];

    /**
     * @inheritDoc
     */
    public string $default = CKP::PHP_MAILER;

    /**
     * @inheritDoc
     */
    public string $adapter = MailgunAdapter::class;

    /**
     * @inheritDoc
     */
    public string $driver = Driver::class;

    /**
     * @inheritDoc
     */
    public string $message = Message::class;

    /**
     * The mailers.
     *
     * @var array[]
     */
    public array $mailers = [
        CKP::LOG        => [
            CKP::ADAPTER => LogAdapter::class,
            CKP::DRIVER  => null,
            CKP::LOGGER  => null,
        ],
        CKP::NULL       => [
            CKP::ADAPTER => NullAdapter::class,
            CKP::DRIVER  => null,
        ],
        CKP::PHP_MAILER => [
            CKP::ADAPTER    => PHPMailerAdapter::class,
            CKP::DRIVER     => null,
            CKP::USERNAME   => '',
            CKP::PASSWORD   => '',
            CKP::HOST       => 'smtp1.example.com;smtp2.example.com',
            CKP::PORT       => 587,
            CKP::ENCRYPTION => 'tls',
        ],
        CKP::MAILGUN    => [
            CKP::ADAPTER => null,
            CKP::DRIVER  => null,
            CKP::DOMAIN  => '',
            CKP::API_KEY => '',
        ],
    ];

    /**
     * @inheritDoc
     */
    public array $messages = [
        CKP::DEFAULT => [
            CKP::ADAPTER      => null,
            CKP::FROM_ADDRESS => 'hello@example.com',
            CKP::FROM_NAME    => 'Example',
        ],
    ];
}
