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
        ConfigName::ADAPTER_CLASS => 'MAILGUN_MAILGUN_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'MAILGUN_MAILGUN_DRIVER_CLASS',
        'apiKey'                  => 'MAILGUN_MAILGUN_API_KEY',
    ];

    public function __construct(
        public string $apiKey = '',
    ) {
        parent::__construct(
            adapterClass: MailgunAdapter::class,
        );
    }
}
