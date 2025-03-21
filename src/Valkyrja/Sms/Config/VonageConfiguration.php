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

namespace Valkyrja\Sms\Config;

use Valkyrja\Sms\Adapter\VonageAdapter;
use Valkyrja\Sms\Constant\ConfigName;

/**
 * Class VonageConfiguration.
 *
 * @author Melech Mizrachi
 */
class VonageConfiguration extends Configuration
{
    /**
     * @inheritDoc
     *
     * @var array<string, string>
     */
    protected static array $envNames = [
        'key'                     => 'SMS_VONAGE_KEY',
        'secret'                  => 'SMS_VONAGE_SECRET',
        ConfigName::ADAPTER_CLASS => 'SMS_VONAGE_ADAPTER_CLASS',
        ConfigName::DRIVER_CLASS  => 'SMS_VONAGE_DRIVER_CLASS',
    ];

    public function __construct(
        public string $key = '',
        public string $secret = ''
    ) {
        parent::__construct(
            adapterClass: VonageAdapter::class,
        );
    }
}
