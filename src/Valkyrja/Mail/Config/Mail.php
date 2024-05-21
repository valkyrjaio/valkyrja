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

use Valkyrja\Application\Constant\EnvKey;
use Valkyrja\Config\Constant\ConfigKeyPart as CKP;
use Valkyrja\Mail\Adapters\LogAdapter;
use Valkyrja\Mail\Adapters\MailgunAdapter;
use Valkyrja\Mail\Adapters\NullAdapter;
use Valkyrja\Mail\Adapters\PHPMailerAdapter;
use Valkyrja\Mail\Config\Config as Model;
use Valkyrja\Mail\Constants\ConfigValue;

use function Valkyrja\env;

/**
 * Class Mail.
 */
class Mail extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array|null $properties = null): void
    {
        $this->updateProperties(ConfigValue::$defaults);

        $this->mailers  = [
            CKP::LOG        => [
                CKP::ADAPTER => env(EnvKey::MAIL_LOG_ADAPTER, LogAdapter::class),
                CKP::DRIVER  => env(EnvKey::MAIL_LOG_DRIVER),
                // null will use default adapter as set in log config
                CKP::LOGGER  => env(EnvKey::MAIL_LOG_LOGGER),
            ],
            CKP::NULL       => [
                CKP::ADAPTER => env(EnvKey::MAIL_NULL_ADAPTER, NullAdapter::class),
                CKP::DRIVER  => env(EnvKey::MAIL_NULL_DRIVER),
            ],
            CKP::PHP_MAILER => [
                CKP::ADAPTER    => env(EnvKey::MAIL_PHP_MAILER_ADAPTER, PHPMailerAdapter::class),
                CKP::DRIVER     => env(EnvKey::MAIL_PHP_MAILER_DRIVER),
                CKP::USERNAME   => env(EnvKey::MAIL_PHP_MAILER_USERNAME, ''),
                CKP::PASSWORD   => env(EnvKey::MAIL_PHP_MAILER_PASSWORD, ''),
                CKP::HOST       => env(EnvKey::MAIL_PHP_MAILER_HOST, 'smtp1.example.com;smtp2.example.com'),
                CKP::PORT       => env(EnvKey::MAIL_PHP_MAILER_PORT, 587),
                CKP::ENCRYPTION => env(EnvKey::MAIL_PHP_MAILER_ENCRYPTION, 'tls'),
            ],
            CKP::MAILGUN    => [
                CKP::ADAPTER => env(EnvKey::MAIL_MAILGUN_ADAPTER, MailgunAdapter::class),
                CKP::DRIVER  => env(EnvKey::MAIL_MAILGUN_DRIVER),
                CKP::DOMAIN  => env(EnvKey::MAIL_MAILGUN_DOMAIN, ''),
                CKP::API_KEY => env(EnvKey::MAIL_MAILGUN_API_KEY, ''),
            ],
        ];
        $this->messages = [
            CKP::DEFAULT => [
                CKP::ADAPTER      => null,
                CKP::FROM_ADDRESS => env(EnvKey::MAIL_FROM_ADDRESS, 'hello@example.com'),
                CKP::FROM_NAME    => env(EnvKey::MAIL_FROM_NAME, 'Example'),
            ],
        ];
    }
}
