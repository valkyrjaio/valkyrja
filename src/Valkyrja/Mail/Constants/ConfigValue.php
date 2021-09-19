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

namespace Valkyrja\Mail\Constants;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Mail\Adapters\LogAdapter;
use Valkyrja\Mail\Adapters\MailgunAdapter;
use Valkyrja\Mail\Adapters\NullAdapter;
use Valkyrja\Mail\Adapters\PHPMailerAdapter;
use Valkyrja\Mail\Drivers\Driver;
use Valkyrja\Mail\Messages\Message;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const DEFAULT         = CKP::PHP_MAILER;
    public const DEFAULT_MESSAGE = CKP::DEFAULT;
    public const ADAPTER         = MailgunAdapter::class;
    public const DRIVER          = Driver::class;
    public const MESSAGE         = Message::class;
    public const MAILERS         = [
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
    public const MESSAGES        = [
        CKP::DEFAULT => [
            CKP::ADAPTER      => null,
            CKP::FROM_ADDRESS => 'hello@example.com',
            CKP::FROM_NAME    => 'Example',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT         => self::DEFAULT,
        CKP::DEFAULT_MESSAGE => self::DEFAULT_MESSAGE,
        CKP::ADAPTER         => self::ADAPTER,
        CKP::DRIVER          => self::DRIVER,
        CKP::MESSAGE         => self::MESSAGE,
        CKP::MAILERS         => self::MAILERS,
        CKP::MESSAGES        => self::MESSAGES,
    ];
}
