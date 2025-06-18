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

namespace Valkyrja\Mail\Constant;

/**
 * Class EnvName.
 *
 * @author Melech Mizrachi
 */
final class EnvName
{
    public const string DEFAULT_CONFIGURATION         = 'MAIL_DEFAULT_CONFIGURATION';
    public const string CONFIGURATIONS                = 'MAIL_CONFIGURATIONS';
    public const string DEFAULT_MESSAGE_CONFIGURATION = 'MAIL_DEFAULT_MESSAGE_CONFIGURATION';
    public const string MESSAGE_CONFIGURATIONS        = 'MAIL_MESSAGE_CONFIGURATIONS';

    public const string MAILGUN_ADAPTER_CLASS = 'MAIL_MAILGUN_ADAPTER_CLASS';
    public const string MAILGUN_DRIVER_CLASS  = 'MAIL_MAILGUN_DRIVER_CLASS';
    public const string MAILGUN_API_KEY       = 'MAIL_MAILGUN_API_KEY';
    public const string MAILGUN_DOMAIN        = 'MAIL_MAILGUN_DOMAIN';

    public const string PHP_MAILER_ADAPTER_CLASS = 'MAIL_PHP_MAILER_ADAPTER_CLASS';
    public const string PHP_MAILER_DRIVER_CLASS  = 'MAIL_PHP_MAILER_DRIVER_CLASS';
    public const string PHP_MAILER_HOST          = 'MAIL_PHP_MAILER_HOST';
    public const string PHP_MAILER_PORT          = 'MAIL_PHP_MAILER_PORT';
    public const string PHP_MAILER_USERNAME      = 'MAIL_PHP_MAILER_USERNAME';
    public const string PHP_MAILER_PASSWORD      = 'MAIL_PHP_MAILER_PASSWORD';
    public const string PHP_MAILER_ENCRYPTION    = 'MAIL_PHP_MAILER_ENCRYPTION';

    public const string LOG_ADAPTER_CLASS = 'MAIL_LOG_ADAPTER_CLASS';
    public const string LOG_DRIVER_CLASS  = 'MAIL_LOG_DRIVER_CLASS';
    public const string LOG_LOGGER        = 'MAIL_LOG_LOGGER';

    public const string NULL_ADAPTER_CLASS = 'MAIL_NULL_ADAPTER_CLASS';
    public const string NULL_DRIVER_CLASS  = 'MAIL_NULL_DRIVER_CLASS';
}
