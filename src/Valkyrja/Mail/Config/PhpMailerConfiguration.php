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

use Valkyrja\Mail\Adapter\PHPMailerAdapter;
use Valkyrja\Mail\Constant\ConfigName;

/**
 * Class PhpMailerConfiguration.
 *
 * @author Melech Mizrachi
 */
class PhpMailerConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => 'PHPMAILER_PHP_MAILER_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'PHPMAILER_PHP_MAILER_DRIVER_CLASS',
        'host'                    => 'PHPMAILER_PHP_MAILER_HOST',
        'port'                    => 'PHPMAILER_PHP_MAILER_PORT',
        'username'                => 'PHPMAILER_PHP_MAILER_USERNAME',
        'password'                => 'PHPMAILER_PHP_MAILER_PASSWORD',
        'encryption'              => 'PHPMAILER_PHP_MAILER_ENCRYPTION',
    ];

    public function __construct(
        public string $host = '',
        public int $port = 25,
        public string $username = '',
        public string $password = '',
        public string $encryption = '',
    ) {
        parent::__construct(
            adapterClass: PHPMailerAdapter::class,
        );
    }
}
