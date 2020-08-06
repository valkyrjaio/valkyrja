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
use Valkyrja\Mail\Messages\Message;

/**
 * Constant ConfigValue.
 *
 * @author Melech Mizrachi
 */
final class ConfigValue
{
    public const FROM_ADDRESS = 'hello@example.com';
    public const FROM_NAME    = 'Example';
    public const ADAPTER      = CKP::PHP_MAILER;
    public const ADAPTERS     = [
        CKP::LOG        => [
            CKP::DRIVER => LogAdapter::class,
        ],
        CKP::NULL       => [
            CKP::DRIVER => NullAdapter::class,
        ],
        CKP::PHP_MAILER => [
            CKP::DRIVER     => PHPMailerAdapter::class,
            CKP::USERNAME   => '',
            CKP::PASSWORD   => '',
            CKP::HOST       => 'smtp1.example.com;smtp2.example.com',
            CKP::PORT       => 587,
            CKP::ENCRYPTION => 'tls',
        ],
        CKP::MAILGUN    => [
            CKP::DRIVER  => MailgunAdapter::class,
            CKP::DOMAIN  => '',
            CKP::API_KEY => '',
        ],
    ];
    public const MESSAGE      = CKP::DEFAULT;
    public const MESSAGES     = [
        CKP::DEFAULT => Message::class,
    ];

    public static array $defaults = [
        CKP::FROM_ADDRESS => self::FROM_ADDRESS,
        CKP::FROM_NAME    => self::FROM_NAME,
        CKP::ADAPTER      => self::ADAPTER,
        CKP::ADAPTERS     => self::ADAPTERS,
        CKP::MESSAGE      => self::MESSAGE,
        CKP::MESSAGES     => self::MESSAGES,
    ];
}
