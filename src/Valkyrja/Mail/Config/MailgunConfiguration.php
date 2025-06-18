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

use Valkyrja\Mail\Adapter\MailgunAdapter;
use Valkyrja\Mail\Constant\ConfigName;
use Valkyrja\Mail\Constant\EnvName;

/**
 * Class MailgunConfiguration.
 *
 * @author Melech Mizrachi
 */
class MailgunConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        ConfigName::ADAPTER_CLASS => EnvName::MAILGUN_ADAPTER_CLASS,
        ConfigName::DRIVER_CLASS  => EnvName::MAILGUN_DRIVER_CLASS,
        ConfigName::API_KEY       => EnvName::MAILGUN_API_KEY,
        ConfigName::DOMAIN        => EnvName::MAILGUN_DOMAIN,
    ];

    public function __construct(
        public string $apiKey = '',
        public string $domain = '',
    ) {
        parent::__construct(
            adapterClass: MailgunAdapter::class,
        );
    }
}
