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

namespace Valkyrja\SMS\Config;

use Valkyrja\Config\Constants\ConfigKeyPart as CKP;
use Valkyrja\Config\Constants\EnvKey;
use Valkyrja\SMS\Adapters\LogAdapter;
use Valkyrja\SMS\Adapters\NullAdapter;
use Valkyrja\SMS\Config\Config as Model;

use function Valkyrja\env;

/**
 * Class SMS.
 */
class SMS extends Model
{
    /**
     * @inheritDoc
     */
    protected function setup(array $properties = null): void
    {
        $this->messengers = [
            CKP::LOG   => [
                CKP::ADAPTER => env(EnvKey::SMS_LOG_ADAPTER, LogAdapter::class),
                CKP::DRIVER  => env(EnvKey::SMS_LOG_DRIVER),
                // null will use default adapter as set in log config
                CKP::LOGGER  => env(EnvKey::SMS_LOG_LOGGER),
            ],
            CKP::NEXMO => [
                CKP::ADAPTER  => env(EnvKey::SMS_NULL_ADAPTER),
                CKP::DRIVER   => env(EnvKey::SMS_NULL_DRIVER),
                CKP::USERNAME => env(EnvKey::SMS_NEXMO_USERNAME, ''),
                CKP::PASSWORD => env(EnvKey::SMS_NEXMO_PASSWORD, ''),
            ],
            CKP::NULL  => [
                CKP::ADAPTER => env(EnvKey::SMS_NULL_ADAPTER, NullAdapter::class),
                CKP::DRIVER  => env(EnvKey::SMS_NULL_DRIVER),
            ],
        ];
        $this->messages   = [
            CKP::DEFAULT => [
                CKP::ADAPTER   => null,
                CKP::FROM_NAME => env(EnvKey::SMS_FROM_NAME, 'Example'),
            ],
        ];
    }
}
