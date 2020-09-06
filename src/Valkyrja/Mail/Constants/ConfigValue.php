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
    public const DEFAULT          = CKP::PHP_MAILER;
    public const ADAPTERS         = [
        CKP::LOG        => LogAdapter::class,
        CKP::NULL       => NullAdapter::class,
        CKP::PHP_MAILER => PHPMailerAdapter::class,
        CKP::MAILGUN    => MailgunAdapter::class,
    ];
    public const DRIVERS          = [
        CKP::DEFAULT => Driver::class,
    ];
    public const MAILERS          = [
        CKP::LOG        => [
            CKP::ADAPTER => CKP::LOG,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::LOGGER  => null,
        ],
        CKP::NULL       => [
            CKP::ADAPTER => CKP::NULL,
            CKP::DRIVER  => CKP::DEFAULT,
        ],
        CKP::PHP_MAILER => [
            CKP::ADAPTER    => CKP::PHP_MAILER,
            CKP::DRIVER     => CKP::DEFAULT,
            CKP::USERNAME   => '',
            CKP::PASSWORD   => '',
            CKP::HOST       => 'smtp1.example.com;smtp2.example.com',
            CKP::PORT       => 587,
            CKP::ENCRYPTION => 'tls',
        ],
        CKP::MAILGUN    => [
            CKP::ADAPTER => CKP::MAILGUN,
            CKP::DRIVER  => CKP::DEFAULT,
            CKP::DOMAIN  => '',
            CKP::API_KEY => '',
        ],
    ];
    public const DEFAULT_MESSAGE  = CKP::DEFAULT;
    public const MESSAGE_ADAPTERS = [
        CKP::DEFAULT => Message::class,
    ];
    public const MESSAGES         = [
        CKP::DEFAULT => [
            CKP::ADAPTER      => CKP::DEFAULT,
            CKP::FROM_ADDRESS => 'hello@example.com',
            CKP::FROM_NAME    => 'Example',
        ],
    ];

    public static array $defaults = [
        CKP::DEFAULT          => self::DEFAULT,
        CKP::ADAPTERS         => self::ADAPTERS,
        CKP::DRIVERS          => self::DRIVERS,
        CKP::MAILERS          => self::MAILERS,
        CKP::DEFAULT_MESSAGE  => self::DEFAULT_MESSAGE,
        CKP::MESSAGE_ADAPTERS => self::MESSAGE_ADAPTERS,
        CKP::MESSAGES         => self::MESSAGES,
    ];
}
